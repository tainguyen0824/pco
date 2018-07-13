<script>
    var Dom,
    Login = {
        settings: {
          
        },
        Validate: function() {
            $('#frm-login').validate({ 
                rules: {
                    email:{
                        required: true,
                        email: true
                    },
                    password:{
                        required: true,
                        minlength: 4
                    },
                },
                messages: {
                    email:{
                        required: "Email is required."
                    },
                    password:{
                        required: "Password is required."
                    },
                },
                submitHandler : function(form) {
                    form.submit();
                }
            });
        },
        Ready: function() {
            Login.Validate();
        },
        init: function() {
            this.Ready();
        }
    };
    $(document).ready(function() {
        Login.init();
    });
</script>