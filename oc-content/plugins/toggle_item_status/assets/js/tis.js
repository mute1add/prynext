/*
Plugin Name: Toggle Item Status
Plugin URI: http://amfearliath.tk/osclass-toggle-item-status
Description: User can mark items as sold or make them available again
Version: 1.0.2
Author: Liath
Author URI: http://amfearliath.tk
Short Name: toggle_item_status
Plugin update URI: toggle-item-status

DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE 
Version 2, December 2004 

Copyright (C) 2004 Sam Hocevar <sam@hocevar.net> 

Everyone is permitted to copy and distribute verbatim or modified 
copies of this license document, and changing it is allowed as long 
as the name is changed. 

DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE 
TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION 

0. You just DO WHAT THE FUCK YOU WANT TO.
*/

$(document).ready(function($) {    
    $(document).on("submit", "#tis_sold", function(event){        
        event.preventDefault();
               
        var form    = $(this),
            action  = form.attr("action"),
            method  = form.attr("method"),
            data    = form.serialize();
        
        form.fadeOut(400);
            
        $.ajax({
            url: action,
            type: method,
            data: data,
            cache: false,            
            success: function(data){
            
                var source = $('<div>' + data + '</div>');                        
                    prefix   = source.find('#tis_status_box').html();
                    button   = source.find('#toggle_item_status').html();
                           
                $('#tis_status_box').html(prefix);
                $('#toggle_item_status').html(button);
                $('#tis_status_box').toggleClass("active");
                if ($('#tis_status_box').hasClass("active")) {
                    
                }
                form.fadeIn(400);
            }
        });        
    });
});