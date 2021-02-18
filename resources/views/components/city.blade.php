<button id="close" class="absolute top-0 right-0 border-2 border-black rounded-lg cursor-pointer hover:bg-gray-700">X</button>
<h2>{{__("Cities :")}}</h2>
@foreach($results as $result)
<div class="city-block border-2 m-2 p-2 border-black rounded-lg">
    <div id="name">{{__("Name : ")}} {{ $result->name }}</div>
    <div id="country">{{__("Country : ")}} {{ $result->country }}</div>
    <div id="description">{{__("Description : ")}} {{ $result->description }}</div>
    <div id="comments">
        <form class="add-comment-form flex justify-between items-center my-2">
            @csrf
            <textarea name="new_comment" class="block mt-1 w-1/2" required> </textarea><br>
            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
            <input type="hidden" name="city_id" value="{{ $result->id }}">
            <x-button class="add-comment-btn mt-4">{{ __('Add New Comment') }}</x-button>
        </form>
        @if($result->comments)
        <p>{{__("Traveler comments : ")}} </p>
        <ul style="none" id="comment-list">
            @foreach($result->comments as $comment)
                @include('components/comments')
            @endforeach
        </ul>
        @endif
    </div>
</div>
@endforeach
<script>
    $('.add-comment-form').on('submit', function(e) {
        e.preventDefault();

        var self = this;
        var button = $(".add-comment-button");

        button.html('Please Wait...');
        button.attr('disabled', true);

        $.ajax({
            url:"comments/",
            type:"POST",
            data: new FormData(self),
            processData: false,
            contentType: false,
            success:function(response) {
                button.html('Add New Comment');
                button.attr('disabled', false);
                self.reset();
                alert(response.message + '\n' + 'Please redo search to see new comment');
            },
            error: function(xhr, status, error){
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                button.html('Add New Comment');
                button.attr('disabled', false);
                alert('Error - ' + errorMessage + '\n' + xhr.responseJSON.message);
            }
        })
    });
    $('#close').click(function(){
        $(this).parent().html("");
    });
</script>