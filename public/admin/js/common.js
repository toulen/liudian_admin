$.extend($.validator.messages, {
    required: "这是必填字段",
    remote: "请修正此字段",
    email: "请输入有效的电子邮件地址",
    url: "请输入有效的网址",
    date: "请输入有效的日期",
    dateISO: "请输入有效的日期 (YYYY-MM-DD)",
    number: "请输入有效的数字",
    digits: "只能输入数字",
    creditcard: "请输入有效的信用卡号码",
    equalTo: "你的输入不相同",
    extension: "请输入有效的后缀",
    maxlength: $.validator.format("最多可以输入 {0} 个字符"),
    minlength: $.validator.format("最少要输入 {0} 个字符"),
    rangelength: $.validator.format("请输入长度在 {0} 到 {1} 之间的字符串"),
    range: $.validator.format("请输入范围在 {0} 到 {1} 之间的数值"),
    max: $.validator.format("请输入不大于 {0} 的数值"),
    min: $.validator.format("请输入不小于 {0} 的数值")
});

jQuery.validator.addMethod("isphone", function(value, element) {
    var tel = /^1[0-9]{10}$/;
    return this.optional(element) || (tel.test(value));
}, "请正确填写您的手机号码");

function postRequest(url, data, showloading, cb, cerr){
    var csrfToken = $('meta[name="csrf_token"]').attr('content');
    if(typeof data !== 'string'){
        data['_token'] = csrfToken;
    }
    if(showloading){
        var index = layer.load(1, {
            shade: false, //0.1透明度的白色背景
            time: 0
        });
    }
    $.ajax({
        url: url,
        method: 'POST',
        dataType: 'Json',
        data: data,
        success: function (res){
            layer.closeAll();
            if(res.status == 0){
                layer.msg(res.msg);
                if(cerr){
                    cerr(res);
                }
            }else {
                if (cb) {
                    cb(res);
                }
            }
        },error: function (res){
            layer.closeAll();
            if(res.responseJSON && res.responseJSON.errors){
                var error = [];
                for(var i in res.responseJSON.errors){
                    error.push(res.responseJSON.errors[i][0]);
                }
                layer.msg(error.join(','))
            }

            if(cerr){
                cerr(res);
            }
        }
    })
}

function fixWindow(url, title, endFn){
    if(!endFn){
        endFn = function (){}
    }
    layer.open({
        type: 2,
        title: title,
        shadeClose: true,
        shade: 0.8,
        area: ['80%', '90%'],
        content: url,
        end: endFn
    });
}

$(function (){
    if($('.checkColorForm').length > 0){
        $('.checkColorForm').find('input').each(function (){
            if($(this)[0].hasAttribute('required')){
                $(this).parents('.form-group').find('label').css('color', 'red');
                $(this).parents('.form-group').find('label').append('*');
            }
        })
        $('.checkColorForm').find('select').each(function (){
            if($(this)[0].hasAttribute('required')){
                $(this).parents('.form-group').find('label').css('color', 'red');
                $(this).parents('.form-group').find('label').append('*');
            }
        })
        $('.checkColorForm').find('textarea').each(function (){
            if($(this)[0].hasAttribute('required')){
                $(this).parents('.form-group').find('label').css('color', 'red');
                $(this).parents('.form-group').find('label').append('*');
            }
        })
    }

    if($('#createdForm').length > 0){
        $('#createdForm').submit(function (){

            var data = $(this).serializeJSON();
            var indexUrl = $(this).attr('data-index');

            postRequest($(this).attr('action'), data, true, function (res){
                //询问框
                layer.confirm('恭喜你！保存成功！接下来？', {
                    btn: ['继续创建','去列表页'] //按钮
                }, function(){
                    window.location.href = '';
                }, function(){
                    window.location.href = indexUrl;
                });
            })
            return false;
        })
    }

    if($('#fixCreatedForm').length > 0){
        $('#fixCreatedForm').submit(function (){

            var data = $(this).serializeJSON();
            var indexUrl = $(this).attr('data-index');

            postRequest($(this).attr('action'), data, true, function (res){
                //询问框
                parent.layer.closeAll();
            })
            return false;
        })
    }

    if($('#editForm').length > 0){
        $('#editForm').submit(function (){

            var data = $(this).serializeJSON();
            var indexUrl = $(this).attr('data-index');

            postRequest($(this).attr('action'), data, true, function (res){
                window.location.href = indexUrl;
            })
            return false;
        })
    }
});