@extends('layouts.app')
@section('content')


    <div id="content">
        <div style="margin-top:50px">
            <div class="container ">

                <div class="col-md-6">
                    <fieldset class="the-fieldset col-md-12 m-b-30">
                        <legend class="the-legend">{{trans('site.contact_info')}}</legend>
                        <div class="col-md-12 p-b-15">
                            <i class="fa fa-phone" aria-hidden="true"></i><span> {{ $con->phone }} </span>
                        </div>
                        <div class="col-md-12">
                            <i class="fa fa-envelope-o" aria-hidden="true"></i> <span> {{ $con->address }}</span>
                        </div>
                    </fieldset>

                </div>
                <div class="col-md-6">
                    <form class="leave-comment text-aria">
                        <fieldset class="the-fieldset col-md-12 m-b-30">
                            <legend class="the-legend">{{trans('site.contact_us')}}</legend>

                            <div class="form-group required">
                                <label class="col-sm-4 control-label" for="input-name">{{trans('site.name')}}</label>
                                <div class="col-sm-8">
                                    <input type="text" name="name" id="input-name" class="form-control"/>
                                </div>
                            </div>

                            <div class="form-group required">
                                <label class="col-sm-4 control-label" for="input-email">{{trans('site.email')}}</label>
                                <div class="col-sm-8">
                                    <input type="text" name="email" id="input-email" class="form-control email">
                                </div>
                            </div>


                            <div class="form-group required">
                                <label class="col-sm-4 control-label" for="input-enquiry">{{trans('site.message')}}</label>
                                <div class="col-sm-8">
                                    <textarea name="enquiry" rows="10" id="input-enquiry"
                                              class="form-control message"></textarea>
                                </div>
                            </div>
                            <strong>
                                <div class="msg_contactus"></div>
                            </strong>
                            <div class="buttons">
                                <div class="pull-right">
                                    <input class="btn btn-success messagecontact" value="{{trans('site.submit')}}">
                                </div>
                            </div>


                        </fieldset>
                    </form>

                </div>
            </div>
        </div>
    </div>












@endsection
@section('script')
{{--    <script src="{{ URL::to('resources/assets/front/js/js.js')}}"></script>--}}
    <script>
        $('.messagecontact').on('click', function () {
            var name = $('#input-name').val();
            var email = $('.email').val();
            var message = $('#input-enquiry').val();
            console.log(name);
            console.log(email);
            console.log(message);
            if (name != '' && email != '' && message != '') {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: " {{url('newcontactus')}}",
                    method: 'POST',
                    data: {name: name, email: email, message: message},
                    success: function (data) {
                        var s = $('.msg_contactus');

                        var ht = '';
                        ht += '<div class="alert alert-success text-center" style="margin-top:20px;">' + data + '</div>';
                        $('#input-name').val('');
                        $('.email').val('');
                        $('#input-enquiry').val('');
                        s.html(ht);
                    }
                });
            } else {
                alert('Please Full Your fields !');
            }


        });

    </script>
@endsection