@extends('layouts.app')
@section('title', 'Kalender Kegiatan')
@section('content')
    <div class="container m-5 pt-5">
        <div class="row">
            {{-- <div class="row g-4 mb-5">
            
            <div class="col-md-4">
                <div class="card shadow-lg border-start rounded-3 h-100" style="background-color: #DC3545">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-calendar3 me-3 text-info" style="font-size: 2rem;"></i>
                            <div>
                                <h5 class="card-title text-white mb-0">TOTAL KEGIATAN</h5>
                                <h1 class="display-5 fw-bold">{{ $stats['total'] ?? 'N/A' }}</h1>
                                <p class="small text-muted mb-0">Periode Tahun {{ $stats['current_year'] ?? 'Ini' }}</p>
                                <h1 class="display-5 fw-bold text-white">20 </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card shadow-lg border-start border-4 border-success h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-check-circle-fill me-3 text-success" style="font-size: 2rem;"></i>
                            <div>
                                <h5 class="card-title text-success mb-0">KEGIATAN SELESAI</h5>
                                <h1 class="display-5 fw-bold">{{ $stats['completed'] ?? 'N/A' }}</h1>
                                <h1 class="display-5 fw-bold">2</h1>
                            </div>
                        </div>
                        <a href="#" class="btn btn-sm btn-outline-success mt-auto fw-bold">
                            <i class="bi bi-folder-fill me-2"></i> Kunjungi Arsip
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card shadow-lg border-start border-4 border-warning h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-clock-fill me-3 text-warning" style="font-size: 2rem;"></i>
                            <div>
                                <h5 class="card-title text-warning mb-0">BELUM DILAKSANAKAN</h5>
                                <h1 class="display-5 fw-bold">{{ $stats['upcoming'] ?? 'N/A' }}</h1>
                                <h1 class="display-5 fw-bold">21</h1>
                            </div>
                        </div>
                        <a href="#" class="btn btn-sm btn-outline-warning mt-auto fw-bold">
                            <i class="bi bi-pencil-square me-2"></i> Daftar Kegiatan
                        </a>
                    </div>
                </div>
            </div>
            
        </div> --}}
            <h1 class="my-3 p-0" style="font-weight:600; font-size: 1.8rem; color: #88304E;">
                Selamat Datang, {{ Auth::user()->nama_lengkap ?? 'Warga' }} 👋
            </h1>
            <div class="card shadow-xl border-gray-200">
                <div class="card-body p-1 md:p-6">
                    {{-- Kalender akan dirender di sini --}}
                    <div id="calendar" style="width: 100%;height:100vh"></div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
        </script>

        <script type="text/javascript">
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var calendarEl = document.getElementById('calendar');
            var events = [];
            var calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                initialView: 'dayGridMonth',
                displayEventTime: false,
                timeZone: 'UTC',
                events: '/events',
                editable: false,

                eventClick: function(info) {
                    // 1. Ambil ID kegiatan dari event yang diklik
                    var eventId = info.event.id;

                    // 2. Arahkan ke halaman detail
                    // Pastikan '/schedules/register/' sesuai dengan route Laravel Anda untuk view detail warga
                    // Contoh Route Laravel: Route::get('/schedules/register/{schedule}', ...)
                    window.location.href = '/daftar-kegiatan/' + eventId;

                    // Mencegah browser melakukan aksi default (jika ada atribut href di event)
                    info.jsEvent.preventDefault();
                },

                // Deleting The Event
                // eventContent: function(info) {
                //     var eventTitle = info.event.title;
                //     var eventElement = document.createElement('div');
                //     eventElement.innerHTML = '<span style="cursor: pointer;">❌</span> ' + eventTitle;

                //     eventElement.querySelector('span').addEventListener('click', function() {
                //         if (confirm("Are you sure you want to delete this event?")) {
                //             var eventId = info.event.id;
                //             $.ajax({
                //                 method: 'get',
                //                 url: '/schedule/delete/' + eventId,
                //                 headers: {
                //                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                //                 },
                //                 success: function(response) {
                //                     console.log('Event deleted successfully.');
                //                     calendar.refetchEvents(); // Refresh events after deletion
                //                 },
                //                 error: function(error) {
                //                     console.error('Error deleting event:', error);
                //                 }
                //             });
                //         }
                //     });
                //     return {
                //         domNodes: [eventElement]
                //     };
                // },

                // Drag And Drop

                eventDrop: function(info) {
                    var eventId = info.event.id;
                    var newStartDate = info.event.start;
                    var newEndDate = info.event.end || newStartDate;
                    var newStartDateUTC = newStartDate.toISOString().slice(0, 10);
                    var newEndDateUTC = newEndDate.toISOString().slice(0, 10);

                    $.ajax({
                        method: 'post',
                        url: `/schedule/${eventId}`,
                        data: {
                            '_token': "{{ csrf_token() }}",
                            start_date: newStartDateUTC,
                            end_date: newEndDateUTC,
                        },
                        success: function() {
                            console.log('Event moved successfully.');
                        },
                        error: function(error) {
                            console.error('Error moving event:', error);
                        }
                    });
                },

                // Event Resizing
                eventResize: function(info) {
                    var eventId = info.event.id;
                    var newEndDate = info.event.end;
                    var newEndDateUTC = newEndDate.toISOString().slice(0, 10);

                    $.ajax({
                        method: 'post',
                        url: `/schedule/${eventId}/resize`,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            end_date: newEndDateUTC
                        },
                        success: function() {
                            console.log('Event resized successfully.');
                        },
                        error: function(error) {
                            console.error('Error resizing event:', error);
                        }
                    });
                },
            });

            calendar.render();

            document.getElementById('searchButton').addEventListener('click', function() {
                var searchKeywords = document.getElementById('searchInput').value.toLowerCase();
                filterAndDisplayEvents(searchKeywords);
            });


            function filterAndDisplayEvents(searchKeywords) {
                $.ajax({
                    method: 'GET',
                    url: `/events/search?title=${searchKeywords}`,
                    success: function(response) {
                        calendar.removeAllEvents();
                        calendar.addEventSource(response);
                    },
                    error: function(error) {
                        console.error('Error searching events:', error);
                    }
                });
            }


            // Exporting Function
            document.getElementById('exportButton').addEventListener('click', function() {
                var events = calendar.getEvents().map(function(event) {
                    return {
                        title: event.title,
                        start: event.start ? event.start.toISOString() : null,
                        end: event.end ? event.end.toISOString() : null,
                        color: event.backgroundColor,
                    };
                });

                var wb = XLSX.utils.book_new();

                var ws = XLSX.utils.json_to_sheet(events);

                XLSX.utils.book_append_sheet(wb, ws, 'Events');

                var arrayBuffer = XLSX.write(wb, {
                    bookType: 'xlsx',
                    type: 'array'
                });

                var blob = new Blob([arrayBuffer], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });

            })
        </script>
    @endsection
