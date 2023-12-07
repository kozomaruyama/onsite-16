<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\LineBotService as LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;
use App\Models\v2_schedule;
use App\Models\User;
use App\Models\Line_message;

class LineWebhookController extends Controller
{
    public function message(Request $request) {

        $data = $request->all();
        $events = $data['events'];
        
// logger()->info($events);        
        $httpClient = new CurlHTTPClient(config('services.line.channel_token'));
        $bot = new LINEBot($httpClient, ['channelSecret' => config('services.line.channel_secret')]);
        $Line_message_class = 0;
        foreach ($events as $event) {
            $id=$event['source']['userId'];
            if ($event['type'] == 'follow') {
            } else if ($event['type'] == 'message') {
                if ( $event['message']['type'] == 'text') {
                    if ( $event['message']['text'] == 'システム連動登録') {
                        // $user = User::where('line_id',$id)->exists();
                        if (User::where('line_id',$id)->exists()) {
                            $message_data1 =  '既に連動されています';
                        } else {
                            $Line_message_class = 1;
                            $message_data1 = 'ログインIDを入力して下さい' ;
                        }
                    } else {
                        $line_message = Line_message::
                                where('source_userId',$event['source']['userId'])->
                                where('class','>',0)->
                                orderBy('timestamp','DESC')->
                                orderBy('class','DESC')->
                                first();
                        switch ($line_message['class']) {
                            case 1:
                                $Line_message_class = 2;
                                $message_data1 = 'パスワードを入力して下さい' ;
                                break;
                            case 2:
                                $Line_message_class = 3;
                                $loginName = Line_message::
                                        select('message_text')->
                                        where('source_userId',$event['source']['userId'])->
                                        where('class', 2)->
                                        orderBy('timestamp','DESC')->
                                        first();
                                $pass = $event['message']['text'];
                                $line_id = $event['source']['userId'];
// logger()->info($loginName['message_text']);
// logger()->info($pass);
                                if (Auth::attempt(['name' => $loginName,'password' => $pass])) {
                                    User::where('name',$loginName['message_text'])->update(['line_id' => $line_id ]);
                                    $message_data1 = '正常に連動されました。' ;
                                } else {
                                    $message_data1 = '連動できません。「ログインID」「パスワード」を確認して下さい。' ;
                                }
                                Line_message::
                                    where('source_userId',$event['source']['userId'])->
                                    where('class','>',0)->
                                    update(['class' => 0]);
                                break;
                        }
                    }
                }
            }

            $Line_message = new Line_message();
            $Line_message->create([
                'type' => $event['type'],
                'message_type' => $event['message']['type'],
                'message_id' => $event['message']['id'],
                'message_text' => $event['message']['text'],
                'webhookEventId' => $event['webhookEventId'],
                'deliveryContext_isRedelivery' => $event['deliveryContext']['isRedelivery'],
                'timestamp' => $event['timestamp'],
                'source_type' => $event['source']['type'],
                'source_userId' => $event['source']['userId'],
                'replyToken' => $event['replyToken'],
                'mode' => $event['mode'],
                'class' => $Line_message_class,
            ]);
            $response = $bot->replyText($event['replyToken'],  $message_data1);
// logger()->info($event['message']['text']);
        }
        return;
    }

        // メッセージ送信用
    public function pushMessage( $message,$line_id) {

        // LINEBOTSDKの設定
        $httpClient = new CurlHTTPClient(config('services.line.channel_token'));
        $bot = new LINEBot($httpClient, ['channelSecret' => config('services.line.messenger_secret')]);     

        // メッセージ送信
        $textMessageBuilder = new TextMessageBuilder($message);
        $response  = $bot->pushMessage($line_id, $textMessageBuilder);

    }

    public function alarmCheck() {
// logger()->info("alarmCheck");
        $time1 = time() ;
        $time2 = time() + 60;

        // $schedules = Schedule::
        //     where('status',1)->
        //     where('start_timestamp','>=',$time1)->
        //     where('start_timestamp','<',$time2)->
        //     get();
        $schedules = v2_schedule::
            where('status',1)->
            where('alarm_timestamp','>=',$time1)->
            where('alarm_timestamp','<',$time2)->
            get();
// logger()->info("alarmCheck",$schedules);
        foreach ($schedules as $schedule){
            $user = User::where('person_id',$schedule['person_id'])->first();
// logger()->info($user);
            if ($user['line_id']!=null) {
                $this->pushMessage($schedule['name'],$user['line_id']);
            }
        }

    }

}