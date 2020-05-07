$(document).ready(function (a, b, c) {
    $('.add-smile').on('click', function (e) {
        var textarea = $('#comment-text'),
            textareaCursorPosition = textarea.prop("selectionStart"),
            el = $(e.currentTarget),
            smile = el.data('smile'),
            textareaVal = textarea.val(),
            newTextareaVal;
        console.log(textareaCursorPosition);
        textareaValSub1 = textareaVal.substring(0, textareaCursorPosition);
        textareaValSub2 = textareaVal.substring(textareaCursorPosition, textareaVal.length);
        newTextareaVal = textareaValSub1 + ' [:' + smile + '] ' + textareaValSub2;
        textarea.focus();
        textarea.val(newTextareaVal);
    });
});