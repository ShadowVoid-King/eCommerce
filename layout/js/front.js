$(function () {
    'use strict';


    // Switch Between Login & Signup

    $(".login-page h1 span").click(function () {
        // Add Class selected           > .login-page h1 span who data-class = ?
        $(this).addClass('selected').siblings().removeClass();
        // Hide All Form
        $('.login-page form').hide();
        // Show Only Form Whose Have class
   // . for class  -----------------> Selector
        $('.' + $(this).data('class')).fadeIn(100);
    });

    // Trigger The Selectboxit

    $("select").selectBoxIt({
        autoWidth: false
    });

    // Hide Placeholder On Form Focus
    $('[placeholder]').focus(function () {
        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder', '');
    }).blur(function () {
        $(this).attr('placeholder', $(this).attr('data-text'));
    });

    // Add Asterisk On Required Field
    $('input').each(function () {
        if ($(this).attr('required') == 'required') {
            $(this).after('<span class="asterisk">*</span>');
        }
    });

    // Confirmation Message On Button
    $('.confirm').click(function () {
        
        return confirm("Are You Sure ?");
        
    });

    // Function Live Name Work Fine
    /*
    function $liveName(live, livePath, dollar = '') {
        $(live).keyup(function () {
            $(livePath).text( dollar + $(this).val());
        });
    }
    $liveName('.live-name', '.live-preview .caption h3');
    $liveName('.live-desc', '.live-preview .caption p');
    $liveName('.live-price', '.live-preview .price-tag', '$');
    */
    // Elzero Way By Data Custom
    $('.live').keyup(function () {
          // This is selector of data-class live-name live-desc or live-price
        $($(this).data('class')).text($(this).val());
    });
});
