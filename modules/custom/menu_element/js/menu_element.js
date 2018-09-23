(function($, Drupal) {
    Drupal.behaviors.menu_element = {
        attach:function() {
             // $(function() {
             //     console.log("JQUERY IS READY!");
             // });
                $(function () {
                    $('li.button-drop-down').once().click(function(){
                        $(this).children('ul.ul-drop-down').toggleClass("active");
                    });
                });
        }
    }

}(jQuery, Drupal));