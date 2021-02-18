<div class="w-full">
    <!-- Validation Errors -->
    <x-auth-validation-errors class="mb-4" :errors="$errors" />
    <form id="city-search-form">
        @csrf
        <h2 class="mb-4 font-serif font-bold">
            {{ __('Search for City by name :')}}
        </h2>
        <div class="flex justify-between items-center">
            <div class="">
                <x-label for="name" :value="__('City Name')" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" />
            </div>
            <div class="">
                <x-label for="comments" :value="__('Number of comments')" />
                <x-input id="comments" class="block mt-1 w-20" type="number" name="comments" :value="old('comments')" min="0"/>
            </div>
            <div class="mt-4">
                <x-button id="search_one" class="" value="search_one" name="search_action" onclick="searchCity()">
                    {{ __('Search One City') }}
                </x-button>
            </div>
            <div class="mt-4">
                <x-button id="search_all" class="" value="search_all" name="search_action" onclick="searchCity()">
                    {{ __('Search All Cities') }}
                </x-button>
            </div>
        </div>
    </form>
    <div id="city-container" class="mt-4 relative"></div>
    <form id="route-search" class="mt-4">
        @csrf
        <h2 class="mb-4 font-serif font-bold">
            {{ __('Search for Routes by City names :')}}
        </h2>
        <div class="flex justify-between items-center">
            <div class="">
                <x-label for="city_from" :value="__('From :')" />
                <x-input id="city_from" class="block mt-1 w-full" type="text" name="city_from" :value="old('city_from')" required/>
            </div>
            <div class="">
                <x-label for="city_to" :value="__('To :')" />
                <x-input id="city_to" class="block mt-1 w-full" type="text" name="city_to" :value="old('city_to')" required/>
            </div>
            <div class="mt-4">
                <x-button class="" value="search_routes" name="search_routes" id="search_routes">
                    {{ __('Search for Routes') }}
                </x-button>
            </div>
        </div>
    </form>
    <div id="route-container" class="mt-4 relative"></div>
</div>
<script>
    function searchCity() {
        event.preventDefault();
        var id = event.target.id;

        if (event.target.value === 'search_one' && !$("#name").val()) {
            alert('Please enter city name');
        } else {
            $('#city-container').show();
            $("#"+id).html('Please Wait...');
            $("#"+id).attr('disabled', true);
            $.ajax({
                url:"/city/search",
                type:"get",
                data:{
                    name: $("#name").val(),
                    comments: $("#comments").val(),
                    search_action: event.target.value,
                },
                success:function(data) {
                    if (id === 'search_one') {
                        $("#"+id).html('Search One City');
                        $("#"+id).attr('disabled', false);
                    } else {
                        $("#"+id).html('Search All Cities');
                        $("#"+id).attr('disabled', false);
                    }
                    $("#city-container").html(data);
                }
            })
        }
    };

    $("#route-search").on('submit', function(e){
        e.preventDefault();

        var data = $(this).serialize();
        var button = $("#search_routes");

        $('#route-container').show();
        // button.html('Please Wait...');
        // button.attr('disabled', true);

        $.ajax({
            url:"/route/search",
            type:"get",
            data:data,
            success:function(data) {
                // button.html('Search for Routes');
                // button.attr('disabled', false);
                $("#route-container").html(data)
            }
        })
    });
</script>