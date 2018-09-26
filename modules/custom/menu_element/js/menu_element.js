(function($, Drupal) {
    Drupal.behaviors.menu_element = {
        attach:function() {
            $(function () {
                $('li.button-drop-down').once().one().click(function(){
                    $(this).children('ul.ul-drop-down').toggleClass("active").empty();
                    jQuery.ajax({
                        type: "POST",
                        url: "/drop-down",
                        dataType: "json",
                        success: function (rows) {
                            nodeLink = rows;
                            for(var i = 0; i < nodeLink.length; i++) {
                                $('ul.ul-drop-down').append("<li class='li-drop-down'><a href="+nodeLink[i].url_node + "><img class='drop-down-image' src="+nodeLink[i].img+">"+nodeLink[i].title+"</a></li>");
                            }
                            },
                    });
                });
            });
        }
    }

}(jQuery, Drupal));