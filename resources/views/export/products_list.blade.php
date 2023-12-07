<table>
  <thead>
    <tr>
      @foreach ($headers as $header)
      <th>{{ $header }}</th>
      @endforeach
    </tr>
  </thead>
  <tbody>
  @foreach ($details as $detail)
    <tr>
      <!-- <td>{{ $detail->id }}</td>
      <td>{{ $detail->name }}</td> -->
      @foreach ($detail as $key => $value)
      <td>{{ $value }}</td>
      @endforeach      
    </tr>
  @endforeach
  </tbody>
</table>