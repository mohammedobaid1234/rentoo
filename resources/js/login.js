
$("#login").on('submit', function(event){
    event.preventDefault();
    var $this = $(this);
    var buttonText = $this.find('button:submit').text();

    $this.find("button:submit").attr('disabled', true);
    $this.find("button:submit").html('<span class="fas fa-spinner" data-fa-transform="shrink-3"></span>');

    $.post($("meta[name='BASE_URL']").attr("content") + "/login", {
        _token: $("meta[name='csrf-token']").attr("content"),
        mobile_no: $.trim($this.find("input[name='mobile_no']").val()),
        password: $this.find("input[name='password']").val(),
        remember: $this.find("input[name='remember']").val()
    },
    function (response, status) {
        window.location = $("meta[name='BASE_URL']").attr("content") + "/dashboard";
    })
    .fail(function (response) {
        http.fail(response.responseJSON, true);
    })
    .always(function () {
        console.log(mobile_no);
        $this.find("button:submit").attr('disabled', false);
        $this.find("button:submit").html(buttonText);
    });
});
