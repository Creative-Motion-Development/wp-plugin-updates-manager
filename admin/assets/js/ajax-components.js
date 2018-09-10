jQuery(function ($) {

    var initAjaxControls = function () {
        $('.factory-ajax-checkbox').on('change', function (ev) {
            var prefix = $(this).data('prefix');
            var action = $(this).data('action');
            var new_value = $(this).val();



            var data = {};
            data['action'] = prefix + 'change_flag';
            data['theme'] = $(this).data('theme-slug');
            if(!data['theme']){
                data['plugin'] = $(this).data('plugin-slug');
            }
            data['flag'] = $(this).data('action');
            data['value'] = new_value;

            var disable_group = $(this).data('disable-group');
            if(disable_group){
                if(new_value == true){
                    $("."+disable_group).find('button, input').prop('disabled', true);
                    var row = $(this).parents('tr');
                    row.removeClass('active').addClass('inactive');
                }else{
                    $("."+disable_group).find('button, input').prop('disabled', false);
                    var row = $(this).parents('tr');
                    row.removeClass('inactive').addClass('active');
                }

            }
            $.ajax({
                url: ajaxurl,
                method: 'post',
                data: data,
                success: function () {

                },
                error: function () {

                }

            });
        });

    };

    initAjaxControls();
});