<div id="comment-block" class="border-2 m-2 p-2 border-grey rounded-lg">
    @if( Auth::user()->id === $comment->user_id)
    <div class="w-1/2">
        <x-label for="user_comment" :value="__('Your comment :')" />
        <textarea required id="user_comment_{{ $comment->id }}" value={{$comment->description}}
            class="block mt-1 mb-4 w-full" type="text" name="user_comment" :value="old('new_comment')">{{$comment->description}}
        </textarea>
    </div>
    <div id="comment-actions">
        <x-button 
            id="update_comment_{{$comment->id}}" 
            data-url="{{ route('comments.update', $comment->id) }}" 
            onclick="updateComment({{$comment->id}})"
        >{{__("Update")}}</x-button>
        <x-button 
            id="delete-comment_{{$comment->id}}"
            data-url="{{ route('comments.destroy', $comment->id) }}" 
            onclick="deleteComment({{$comment->id}})"
        >{{__("Delete")}}</x-button>
    </div>
    @else
    <div id="comment-text" class="mb-4">{{$comment->description}} {{$comment->user_id}}</div>
    @endif
</div>
<script>
    function updateComment(id) {
        var self = $("#update_comment_"+id);
        var comment = $("#user_comment_"+id).val();

        $.ajax({
            url: self.attr('data-url'),
            type:"PUT",
            data:{
                text: comment,
            },
            success:function(data) {
                alert("Comment Updated");
            }
        })
    };
    function deleteComment(id) {
        confirm("Delete comment ?");
        var self = $("#update_comment_"+id);
        var comment = $("#user_comment_"+id);

        $.ajax({
            url: self.attr('data-url'),
            type:"DELETE",
            success:function(data) {
                alert("Comment Deleted");
                comment.parent().parent().remove();
            }
        })
    };
</script>