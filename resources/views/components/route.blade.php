<button id="close_route" class="absolute top-0 right-0 border-2 border-black rounded-lg cursor-pointer hover:bg-gray-700">X</button>
<div id="source_city">{{__("Source : ")}} {{ $sourceAirport->city }}</div>
<div id="destination_city">{{__("Destination : ")}} {{ $destinationAirport->city }}</div>
<h2>{{__("Routes :")}}</h2>
{{-- @foreach($results as $result)
<div class="route-block border-2 m-2 p-2 border-black rounded-lg">
    <div id="source_city">{{__("Source : ")}} {{ $result->source_city }}</div>
    <div id="destination_city">{{__("Destination : ")}} {{ $result->destination_city }}</div>
    <div id="price">{{__("Price : ")}} {{ $result->price }}</div>
</div>
@endforeach --}}
<script>
    $('#close_route').click(function(){
        $(this).parent().hide();
    });
</script>