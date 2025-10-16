$(document).ready(function() {
    // Select the element containing the text
    var textElement1 = $('#animate1');
    var textElement2 = $('#animate2');
    var textElement3 = $('#animate3');
    var textElement4 = $('#animate4');
    var textElement5 = $('#animate5');
    var textElement6 = $('#animate6');
    var textElement7 = $('#animate7'); 
    var textElement8 = $('#animate8');
    var textElement9 = $('#animate9'); 
    var textElement10 = $('#animate10');
    var textElement11 = $('#animate11'); 
    var textElement12 = $('#animate12');
    var textElement13 = $('#animate13'); 
    var textElement14 = $('#animate14');
    var textElement15 = $('#animate15');
    var textElement16 = $('#animate16'); 
    var textElement17 = $('#animate17');
    var textElement18 = $('#animate18');
    var textElement19 = $('#animate19'); 
    var textElement20 = $('#animate20');
    var textElement21 = $('#animate21'); 

    // Get the text content of the element
    var originalText1 = textElement1.text();
    var originalText2 = textElement2.text();
    var originalText3 = textElement3.text();
    var originalText4 = textElement4.text();
    var originalText5 = textElement5.text();
    var originalText6 = textElement6.text();
    var originalText7 = textElement7.text();
    var originalText8 = textElement8.text();
    var originalText9 = textElement9.text();
    var originalText10 = textElement10.text();
    var originalText11 = textElement11.text();
    var originalText12 = textElement12.text();
    var originalText13 = textElement13.text();
    var originalText14 = textElement14.text();
    var originalText15 = textElement15.text();
    var originalText16 = textElement16.text();
    var originalText17 = textElement17.text();
    var originalText18 = textElement18.text();
    var originalText19 = textElement19.text();
    var originalText20 = textElement20.text();
    var originalText21 = textElement21.text();

    // Split the text into an array of individual characters
    // An empty string as the separator splits the string by each character.
    var charactersArray1 = originalText1.split('');
    var charactersArray2 = originalText2.split('');
    var charactersArray3 = originalText3.split('');
    var charactersArray4 = originalText4.split('');
    var charactersArray5 = originalText5.split('');
    var charactersArray6 = originalText6.split('');
    var charactersArray7 = originalText7.split('');
    var charactersArray8 = originalText8.split('');
    var charactersArray9 = originalText9.split('');
    var charactersArray10 = originalText10.split('');
    var charactersArray11 = originalText11.split('');
    var charactersArray12 = originalText12.split('');
    var charactersArray13 = originalText13.split('');
    var charactersArray14 = originalText14.split('');
    var charactersArray15 = originalText15.split('');
    var charactersArray16 = originalText16.split('');
    var charactersArray17 = originalText17.split('');
    var charactersArray18 = originalText18.split('');
    var charactersArray19 = originalText19.split('');
    var charactersArray20 = originalText20.split('');
    var charactersArray21 = originalText21.split('');

    // You can then manipulate or re-insert the characters as needed.
    // For example, wrapping each character in a span for individual styling:
    var newHtml1 = '';
    $.each(charactersArray1, function(index, char) {
        newHtml1 += '<span class="letter">' + char + '</span>';
    });
    var newHtml2 = '';
    $.each(charactersArray2, function(index, char) {
        newHtml2 += '<span class="letter">' + char + '</span>';
    });
    var newHtml3 = '';
    $.each(charactersArray3, function(index, char) {
        newHtml3 += '<span class="letter">' + char + '</span>';
    });
    var newHtml4 = '';
    $.each(charactersArray4, function(index, char) {
        newHtml4 += '<span class="letter">' + char + '</span>';
    });
    var newHtml5 = '';
    $.each(charactersArray5, function(index, char) {
        newHtml5 += '<span class="letter">' + char + '</span>';
    });
    var newHtml6 = '';
    $.each(charactersArray6, function(index, char) {
        newHtml6 += '<span class="letter">' + char + '</span>';
    });
    var newHtml7 = '';
    $.each(charactersArray7, function(index, char) {
        newHtml7 += '<span class="letter">' + char + '</span>';
    });
    var newHtml8 = '';
    $.each(charactersArray8, function(index, char) {
        newHtml8 += '<span class="letter">' + char + '</span>';
    });
    var newHtml9 = '';
    $.each(charactersArray9, function(index, char) {
        newHtml9 += '<span class="letter">' + char + '</span>';
    });
    var newHtml10 = '';
    $.each(charactersArray10, function(index, char) {
        newHtml10 += '<span class="letter">' + char + '</span>';
    });
    var newHtml11 = '';
    $.each(charactersArray11, function(index, char) {
        newHtml11 += '<span class="letter">' + char + '</span>';
    });
    var newHtml12 = '';
    $.each(charactersArray12, function(index, char) {
        newHtml12 += '<span class="letter">' + char + '</span>';
    });
    var newHtml13 = '';
    $.each(charactersArray13, function(index, char) {
        newHtml13 += '<span class="letter">' + char + '</span>';
    });
    var newHtml14 = '';
    $.each(charactersArray14, function(index, char) {
        newHtml14 += '<span class="letter">' + char + '</span>';
    });
    var newHtml15 = '';
    $.each(charactersArray15, function(index, char) {
        newHtml15 += '<span class="letter">' + char + '</span>';
    });
    var newHtml16 = '';
    $.each(charactersArray16, function(index, char) {
        newHtml16 += '<span class="letter">' + char + '</span>';
    });
    var newHtml17 = '';
    $.each(charactersArray17, function(index, char) {
        newHtml17 += '<span class="letter">' + char + '</span>';
    });
    var newHtml18 = '';
    $.each(charactersArray18, function(index, char) {
        newHtml18 += '<span class="letter">' + char + '</span>';
    });
    var newHtml19 = '';
    $.each(charactersArray19, function(index, char) {
        newHtml19 += '<span class="letter">' + char + '</span>';
    });
    var newHtml20 = '';
    $.each(charactersArray20, function(index, char) {
        newHtml20 += '<span class="letter">' + char + '</span>';
    });
    var newHtml21 = '';
    $.each(charactersArray21, function(index, char) {
        newHtml21 += '<span class="letter">' + char + '</span>';
    });

    // Update the element's HTML with the wrapped characters
    textElement1.html(newHtml1);
    textElement2.html(newHtml2);
    textElement3.html(newHtml3);
    textElement4.html(newHtml4);
    textElement5.html(newHtml5);
    textElement6.html(newHtml6);
    textElement7.html(newHtml7);
    textElement8.html(newHtml8);
    textElement9.html(newHtml9);
    textElement10.html(newHtml10);
    textElement11.html(newHtml11);
    textElement12.html(newHtml12);
    textElement13.html(newHtml13);
    textElement14.html(newHtml14);
    textElement15.html(newHtml15);
    textElement16.html(newHtml16);
    textElement17.html(newHtml17);
    textElement18.html(newHtml18);
    textElement19.html(newHtml19);
    textElement20.html(newHtml20);
    textElement21.html(newHtml21);
    
    
    // Function to check if an element is in the viewport
    function isElementInViewport(el) {
        var rect = el.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }

    // Handle scroll event
    $(window).scroll(function() {
        $('.letter').each(function(index) {
            var $this = $(this);
            if (isElementInViewport(this) && $this.css('opacity') == 0) {
                // Stagger the fade-in for a nicer effect
                setTimeout(function() {
                    $this.animate({ opacity: 1 }, 5); // Fade in over 500ms
                }, index * 50); // Delay each letter's animation
            }
        });
    });

    // Trigger the check on page load in case elements are already in view
    $(window).scroll();
});