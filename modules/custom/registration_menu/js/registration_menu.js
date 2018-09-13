(function($, Drupal)
{
    Drupal.behaviors.registration_menu = {
        attach:function()
        {
            $('.register').each(function(i){
                $(this).click(function(){
                    $('.form-registration').addClass('registerform-active');
                    $('.login').css('background-color','#A9E2F3');
                    $('.register').css('color','#A9E2F3');
                });
            });
            $('.login').each(function(i){
                $(this).click(function(){
                    $('.form-registration').removeClass('registerform-active');
                    $('.register').css('background-color','rgb(115, 140, 147)');
                });
            });

        }
    }

}(jQuery, Drupal));