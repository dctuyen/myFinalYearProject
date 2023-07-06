@extends('layouts.app')
@vite(['public/css/auth.css', 'public/js/auth.js'])
@section('title', 'Lịch học')

@section('content')
    <style>
        .fc-day-today {
            background-color: #EFB0C9!important;
        }
        .fc-event {
            background-color: #fff!important;
            color: black!important;
        }

        .fc-event-title {
            white-space: normal;
            overflow: visible;
            text-overflow: clip;
        }
    </style>
    <div class="card container">
        <table style="width: 100%" class="mt-4">
            <tr>
                <td style="width:1px; white-space: nowrap;">Lịch của tôi</td>
                <td>
                    <hr/>
                </td>
                <td>
                    <hr/>
                </td>
            </tr>
        </table>
        <div id="calendar"></div>
    </div>
@endsection

@section('script')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let calendarEl = document.getElementById('calendar');
            let calendar = new FullCalendar.Calendar(calendarEl, {
                nowIndicator: true,
                initialView: 'dayGridMonth',
                events: @json($calendars),

                eventTimeFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                    meridiem: 'short'
                },
            });
            calendar.render();
        });



    </script>
@endSection
