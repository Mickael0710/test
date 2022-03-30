/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import 'bootstrap/dist/css/bootstrap.min.css';


var $ = require('jquery');
global.$ = global.jQuery = global.jquery = $;

require('bootstrap');
require('popper.js');



$(document).ready(function() {
    var applyShowFunc = function() {
        $('.show-details').off('click').on('click', function(e) {
            var $this = $(this);
            var $modal = $("#modal_api_details");
            $modal.show();
            e.preventDefault();
            $.ajax({
                url: $this.data('custom_uri'),
                method: 'POST',
                data: {'uri': $this.attr('href')},
                success: function (resp) {
                    $modal.find('.modal-body').html('').html(resp.template);
                    applyShowFunc();
                    $(".close").off('click').on('click', function(e) {
                        e.preventDefault();
                        $modal.hide();
                    });
                },
                beforeSend: function() {
                    $modal.find('.modal-body').html('Chargement en cours...');
                }
            });
        });
    };

    var applyPaginate = function() {
        $('.paginate-ws').on('click').on('click', function(e) {
            e.preventDefault();
            var path = $(this).data('url_search');
            var uri = $(this).attr('href');
            $.ajax({
                url: path,
                method: 'POST',
                data: {'uri' : uri},
                success: function (resp) {
                    $(".wrapper-results").html('').html(resp.template);
                    applyShowFunc();
                    applyPaginate();
                },
                beforeSend: function() {
                    $(".wrapper-results").html('Chargement en cours...');
                }
            });
        });
    };

    $("#btnSearch").off('click').on('click', function(e) {
        var path = $(this).data('url_search');
        e.preventDefault();
        $.ajax({
            url: path,
            method: 'POST',
            data: {'search' : $.trim($("#search").val())},
            success: function (resp) {
                $(".wrapper-results").html('').html(resp.template);
                applyShowFunc();
                applyPaginate();
            },
            beforeSend: function() {
                $(".wrapper-results").html('Chargement en cours...');
            }
        });
    });

    setTimeout(function() {
        $('#btnSearch').trigger('click');
    },500);
});