<div class="w-full justify-evenly flex align-center">

    <!-- Validation Errors -->
    <x-auth-validation-errors class="mb-4" :errors="$errors" />

    <form id="add-city-form" class="w-1/3">
        @csrf

        <!-- Name -->
        <div>
            <x-label for="name" :value="__('Name')" />

            <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
        </div>

        <!-- Country -->
        <div class="mt-4">
            <x-label for="country" :value="__('Country')" />

            <x-input id="country" class="block mt-1 w-full" type="text" name="country" :value="old('country')" required />
        </div>

        <!-- Description -->
        <div class="mt-4">
            <x-label for="description" :value="__('Description')" />

            <textarea id="description" class="block mt-1 w-full" type="text" name="description" :value="old('description')" required ></textarea>
        </div>

        <div class="flex justify-center mt-4">
            <x-button class="ml-4 add-city-button">
                {{ __('Add City') }}
            </x-button>
        </div>
    </form>
    <form id="upload-form" class="w-1/3 flex flex-col items-center justify-between" enctype="multipart/form-data">
        @csrf
        <h2 class="mt-4 text-2xl font-serif font-bold">{{ __('Import data files') }}</h2>
        <div class="col-md-3">
            <x-label for="airports" :value="__('Airports:')" />
            <x-input id="airports" type="file" name="airports" required/>
        </div>

        <div class="col-md-3">
            <x-label for="routes" :value="__('Routes:')" />
            <x-input id="routes" type="file" name="routes" required/>
        </div>
        <div class="mt-4">
            <x-button class="import-button">
                {{ __('Import Data') }}
            </x-button>
        </div>
    </form>
</div>
<script>
    $("#add-city-form").on('submit', function(e){
        e.preventDefault();

        var self = this;
        var button = $(".add-city-button");

        button.html('Please Wait...');
        button.attr('disabled', true);

        $.ajax({
            url:"city/add",
            type:"POST",
            data: new FormData(self),
            processData: false,
            contentType: false,
            success:function(data) {
                button.html('Add City');
                button.attr('disabled', false);
                self.reset();
                alert('City Was Added');
            }
        })
    });
    $("#upload-form").on('submit', function(e){
        e.preventDefault();

        var self = this;
        var button = $(".import-button");

        button.html('Please Wait...');
        button.attr('disabled', true);

        $.ajax({
            url:"import/",
            type:"POST",
            data: new FormData(self),
            processData: false,
            contentType: false,
            cache: false,
            success:function(data) {
                button.html('Import Data');
                button.attr('disabled', false);
                self.reset();
                alert('Files Uploaded Successfully');
            },
            error: function(xhr, status, error){
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                button.html('Import Data');
                button.attr('disabled', false);
                alert('Error - ' + errorMessage + '\n' + xhr.responseJSON.message);
            }
        })
    });
</script>