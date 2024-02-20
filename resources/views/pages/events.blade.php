@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Events'])
    <div class="row mt-4 mx-3">
        <div class="col-12">
            <div class="card card-profile mt-5">
                <div class="row justify-content-center">
                    <div class="col-3 col-lg-3 order-lg-2 mt-4">
                        <div class="mt-n4 mt-lg-n6 mb-4 mb-lg-0">
                            <a href="javascript:;">
                                <img src="/img/team-2.jpg" class="rounded-circle img-fluid border border-2 border-white">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-header text-center border-0 pt-0 pt-lg-2 pb-4 pb-lg-3">
                    <div class="d-flex justify-content-center">
                        <a href="javascript:;" class="btn btn-sm btn-success mb-0 d-none d-lg-block">Edit Photo</a>
                    </div>
                    <div class="col-auto my-auto mt-3">
                        <div class="h-100">
                            <h5 class="mb-1">
                                {{ auth()->user()->firstname ?? 'Firstname' }} {{ auth()->user()->lastname ?? 'Lastname' }}
                            </h5>
                            <p class="mb-3 font-weight-bold text-sm">
                                Public Relations
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div id="alert">
        @include('components.alert')
    </div>

    <div class="container-fluid py-3 my-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0 py-3 my-3">
                        <div class="d-flex align-items-center">
                            <p class="mb-0">Edit Profile</p>
                            <button type="submit" class="btn btn-primary btn-sm ms-auto">Save</button>
                        </div>
                        <div id="calendar"></div>
                        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
                        <div id="calendar"></div>             
                    
                        <script>
                            const modal = $('#modal-action')
                            const csrfToken = $('meta[name=csrf_token]').attr('content')
                    
                            document.addEventListener('DOMContentLoaded', function() {
                                var calendarEl = document.getElementById('calendar');
                                var calendar = new FullCalendar.Calendar(calendarEl, {
                                initialView: 'dayGridMonth',
                                themeSystem: 'bootstrap5',
                                events: `{{ route('events.list') }}`,
                                editable: true,
                                dateClick: function (info) {
                                    $.ajax({
                                        url: `{{ route('events.create') }}`,
                                        data: {
                                            start_date: info.dateStr,
                                            end_date: info.dateStr
                                        },
                                        success: function (res) {
                                            modal.html(res).modal('show')
                                            $('.datepicker').datepicker({
                                                todayHighlight: true,
                                                format: 'yyyy-mm-dd'
                                            });
                    
                                            $('#form-action').on('submit', function(e) {
                                                e.preventDefault()
                                                const form = this
                                                const formData = new FormData(form)
                                                $.ajax({
                                                    url: form.action,
                                                    method: form.method,
                                                    data: formData,
                                                    processData: false,
                                                    contentType: false,
                                                    success: function (res) {
                                                        modal.modal('hide')
                                                        calendar.refetchEvents()
                                                    },
                                                    error: function (res) {
                    
                                                    }
                                                })
                                            })
                                        }
                                    })
                                },
                                eventClick: function ({event}) {
                                    $.ajax({
                                        url: `{{ url('events') }}/${event.id}/edit`,
                                        success: function (res) {
                                            modal.html(res).modal('show')
                    
                                            $('#form-action').on('submit', function(e) {
                                                e.preventDefault()
                                                const form = this
                                                const formData = new FormData(form)
                                                $.ajax({
                                                    url: form.action,
                                                    method: form.method,
                                                    data: formData,
                                                    processData: false,
                                                    contentType: false,
                                                    success: function (res) {
                                                        modal.modal('hide')
                                                        calendar.refetchEvents()
                                                    }
                                                })
                                            })
                                        }
                                    })
                                },
                                eventDrop: function (info) {
                                    const event = info.event
                                    $.ajax({
                                        url: `{{ url('events') }}/${event.id}`,
                                        method: 'put',
                                        data: {
                                            id: event.id,
                                            start_date: event.startStr,
                                            end_date: event.end.toISOString().substring(0, 10),
                                            title: event.title,
                                            category: event.extendedProps.category
                                        },
                                        headers: {
                                            'X-CSRF-TOKEN': csrfToken,
                                            accept: 'application/json'
                                        },
                                        success: function (res) {
                                            iziToast.success({
                                                title: 'Success',
                                                message: res.message,
                                                position: 'topRight'
                                            });
                                        },
                                        error: function (res) {
                                            const message = res.responseJSON.message
                                            info.revert()
                                            iziToast.error({
                                                title: 'Error',
                                                message: message ?? 'Something wrong',
                                                position: 'topRight'
                                            });
                                        }
                                    })
                                },
                                eventResize: function (info) {
                                    const {event} = info
                                    $.ajax({
                                        url: `{{ url('events') }}/${event.id}`,
                                        method: 'put',
                                        data: {
                                            id: event.id,
                                            start_date: event.startStr,
                                            end_date: event.end.toISOString().substring(0, 10),
                                            title: event.title,
                                            category: event.extendedProps.category
                                        },
                                        headers: {
                                            'X-CSRF-TOKEN': csrfToken,
                                            accept: 'application/json'
                                        },
                                        success: function (res) {
                                            iziToast.success({
                                                title: 'Success',
                                                message: res.message,
                                                position: 'topRight'
                                            });
                                        },
                                        error: function (res) {
                                            const message = res.responseJSON.message
                                            info.revert()
                                            iziToast.error({
                                                title: 'Error',
                                                message: message ?? 'Something wrong',
                                                position: 'topRight'
                                            });
                                        }
                                    })
                                }
                    
                    
                                });
                                calendar.render();
                            });
                    
                        </script>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection
