@extends('layouts.admin')
@section('title', 'Dashboard Pengurus')
@section('content')
    <style>
        .btn-custom {
            background-color: #88304E;
            color: #fff;
            border: none;
            padding: 0.5rem 1.2rem;
            border-radius: 8px;
            transition: background-color 0.3s ease;
            white-space: nowrap;
        }

        .btn-custom:hover {
            background-color: #a43a63;
            /* warna lebih terang saat hover */
        }

        .btn-custom i {
            font-size: 1.1rem;
        }

        .stat-card {
            background-color: #ffffff;
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        .border-left-primary {
            border-left: 5px solid #88304E;
        }

        .border-left-info {
            border-left: 5px solid #0dcaf0;
        }

        .border-left-success {
            border-left: 5px solid #198754;
        }

        .border-left-warning {
            border-left: 5px solid #ffc107;
        }

        /* Icon Box Styling */
        .icon-box {
            width: 56px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        /* Warna Icon Box Background (Light Version) */
        .bg-light-primary {
            background-color: rgba(136, 48, 78, 0.1);
            color: #88304E;
        }

        .bg-light-info {
            background-color: rgba(13, 202, 240, 0.1);
            color: #0dcaf0;
        }

        .bg-light-success {
            background-color: rgba(25, 135, 84, 0.1);
            color: #198754;
        }

        .bg-light-warning {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .stat-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #6c757d;
            /* Abu-abu agar mata tidak lelah */
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #343A40;
            /* Hitam soft */
            margin-bottom: 0;
        }

        .fc-header-toolbar {
            flex-wrap: wrap;
            /* Agar tombol kalender turun baris di HP */
            gap: 10px;
        }
    </style>
    <div class="container-fluid">
        <div class="content-wrapper px-1 py-1">
            <h1 class="mb-2" style="font-size: 1.8rem; color: #88304E;">
                Selamat datang, {{ Auth::user()->nama_lengkap ?? 'Pengurus' }} 👋
            </h1>
            {{-- disini tambahkan card informasi --}}
            <p style="font-size: 1rem; color: #555;">
                Ada kegiatan baru apa yang ingin ditambahkan?
            </p>
            <a href="{{ route('schedules.create') }}"
                class="btn btn-custom fw-semibold d-inline-flex align-items-center gap-2">
                <i class="bi bi-plus-square-fill"></i>Tambah Kegiatan
            </a>
        </div>

        <div class="row g-4 mt-0 mb-3">

            {{-- CARD 1: TOTAL KEGIATAN --}}
            <div class="col-12 col-md-6 col-xl-4">
                <div class="card stat-card border-left-primary">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="stat-title mb-1">Total Kegiatan</div>
                                {{-- Ganti angka 25 dengan variabel dari controller, misal: {{ $total_kegiatan }} --}}
                                <h2 class="stat-value">{{ $total_kegiatan ?? 0 }}</h2>
                            </div>
                            <div class="icon-box bg-light-primary">
                                <i class="bi bi-calendar-event"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CARD 2: AKAN DATANG --}}
            <div class="col-12 col-md-6 col-xl-4">
                <div class="card stat-card border-left-info">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="stat-title mb-1">Akan Datang</div>
                                <h2 class="stat-value">{{ $akan_datang ?? 0 }}</h2>
                            </div>
                            <div class="icon-box bg-light-info">
                                <i class="bi bi-hourglass-split"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CARD 3: SELESAI --}}
            <div class="col-12 col-md-6 col-xl-4">
                <div class="card stat-card border-left-success">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="stat-title mb-1">Selesai</div>
                                <h2 class="stat-value">{{ $selesai ?? 0 }}</h2>
                            </div>
                            <div class="icon-box bg-light-success">
                                <i class="bi bi-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <div class="card shadow-xl border-2" style="border-color:#88304E; width: 99%;">
        <div class="card-body p-md-4">
            {{-- Kalender akan dirender di sini --}}
            <div id="calendar" style="width: 100%;min-height: 85vh; text-decoration:none;"></div>
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
            timeZone: 'UTC',
            events: '/events',
            editable: true,

            eventClick: function(info) {
                // 1. Ambil ID kegiatan dari event yang diklik
                var eventId = info.event.id;

                // 2. Arahkan ke halaman detail
                // Pastikan '/schedules/register/' sesuai dengan route Laravel Anda untuk view detail warga
                // Contoh Route Laravel: Route::get('/schedules/register/{schedule}', ...)
                window.location.href = '/kegiatan/' + eventId + '/pendaftar';

                // Mencegah browser melakukan aksi default (jika ada atribut href di event)
                info.jsEvent.preventDefault();
            },

            // Deleting The Event
            eventContent: function(info) {
                var eventTitle = info.event.title;
                var eventElement = document.createElement('div');
                eventElement.innerHTML = '<span style="cursor: pointer;"></span> ' + eventTitle;

                eventElement.querySelector('span').addEventListener('click', function() {
                    if (confirm("Are you sure you want to delete this event?")) {
                        var eventId = info.event.id;
                        $.ajax({
                            method: 'get',
                            url: '/schedule/delete/' + eventId,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                console.log('Event deleted successfully.');
                                calendar.refetchEvents(); // Refresh events after deletion
                            },
                            error: function(error) {
                                console.error('Error deleting event:', error);
                            }
                        });
                    }
                });
                return {
                    domNodes: [eventElement]
                };
            },

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
