/*
 * Community Auth - auto-populate.js
 * @ requires jQuery
 *
 * Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 *
 * Licensed under the BSD licence:
 * http://www.opensource.org/licenses/BSD-3-Clause
 */
$(document).ready(function(){

    // Whenever one of the form dropdowns is changed
    $('#year, #term, #courseName').change(function(){
        // When type is changed, reset make and model
        if( $(this).attr('id') == 'year' ){
            $('#term option').attr('selected', false);
            $('#courseName option').attr('selected', false);
            $('#section option').attr('selected', false);
        }else if( $(this).attr('id') == 'term' ){
            $('#courseName option').attr('selected', false);
            $('#section option').attr('selected', false);
        }else if( $(this).attr('id') == 'courseName' ){
            $('#section option').attr('selected', false);
        }
        // Get the CI CSRF token name
        ci_csrf_token_name = $('#ci_csrf_token_name').val();
        // Set post vars
        var post_vars = {
            'year':  $('#year option:selected').val(),
            'term':  $('#term option:selected').val(),
            'courseName':  $('#courseName option:selected').val(),
            'token': $('input[name="token"]').val()
        };
        post_vars[ci_csrf_token_name] = $('input[name="' + ci_csrf_token_name + '"]').val();
        // POST
        $.ajax({
            type: 'post',
            cache: false,
            url: $('#ajax_url').val(),
            data: post_vars,
            dataType: 'json',
            success: function(response, textStatus, jqXHR){
                if(response.status == 'success'){
                    // Update the dropdowns and tokens
                    $('#term').html(response.term);
                    $('#courseName').html(response.courseName);
                    $('#section').html(response.section);
                    $('input[name="token"]').val(response.token);
                    $('input[name="' + ci_csrf_token_name + '"]').val( response.ci_csrf_token );
                }else{
                    alert(response.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                alert('Error: Server Connectivity Error.\nHTTP Error: ' + jqXHR.status + ' ' + errorThrown);
            }
        });
    });

});