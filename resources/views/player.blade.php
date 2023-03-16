<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Player</title>
    <script type='text/javascript' src='//www.midijs.net/lib/midi.js'></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="m-2 md:m-10">

    <div class="container">
        <div class="flex flex-cols bg-slate-300 max-w-sm m-0 p-2 rounded-xl border-2 border-slate-700">

            <div id="play" class="hover:bg-slate-200">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M4.5 5.65306C4.5 4.22693 6.029 3.32288 7.2786 4.01016L18.8192 10.3575C20.1144 11.0698 20.1144 12.9309 18.8192 13.6433L7.2786 19.9906C6.029 20.6779 4.5 19.7738 4.5 18.3477V5.65306Z"
                        fill="#0F172A" />
                </svg>
            </div>

            <div id="pause" class="hover:bg-slate-200">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M6.75 5.25C6.75 4.83579 7.08579 4.5 7.5 4.5H9C9.41421 4.5 9.75 4.83579 9.75 5.25V18.75C9.75 19.1642 9.41421 19.5 9 19.5H7.5C7.30109 19.5 7.11032 19.421 6.96967 19.2803C6.82902 19.1397 6.75 18.9489 6.75 18.75L6.75 5.25ZM14.25 5.25C14.25 4.83579 14.5858 4.5 15 4.5H16.5C16.6989 4.5 16.8897 4.57902 17.0303 4.71967C17.171 4.86032 17.25 5.05109 17.25 5.25L17.25 18.75C17.25 19.1642 16.9142 19.5 16.5 19.5H15C14.5858 19.5 14.25 19.1642 14.25 18.75V5.25Z"
                        fill="#0F172A" />
                </svg>
            </div>

            <div id="stop" class="hover:bg-slate-200">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M4.5 7.5C4.5 5.84315 5.84315 4.5 7.5 4.5H16.5C18.1569 4.5 19.5 5.84315 19.5 7.5V16.5C19.5 18.1569 18.1569 19.5 16.5 19.5H7.5C5.84315 19.5 4.5 18.1569 4.5 16.5V7.5Z"
                        fill="#0F172A" />
                </svg>
            </div>

            <div>
                <label class="text-sm px-2"> <span id="currenttime">00.00</span> / <span id="totaltime">00.00</span>
                </label>
            </div>

            <div class="grow">
                <progress id="progress" class="progress progress-primary" value="0" max="100"></progress>
            </div>

        </div>
    </div>
</body>

<script>
    // console.log(MIDIjs);

    const midifilename = "default.mid"
    var playing = false;
    var totalDuration = 0;

    function getMS(time) {
        const M = Math.floor(time / 60);
        const S = Math.floor(time - M * 60);
        const finalTime = str_pad_left(M, '0', 2) + '.' + str_pad_left(S, '0', 2);
        return [finalTime];
    }

    function str_pad_left(string, pad, length) {
        return (new Array(length + 1).join(pad) + string).slice(-length);
    }

    const playMIDIHandler = function(e) {
        // let midiString = 'data:audio/midi;base64,' + base64midi;
        play.style.visibility = "collapse";
        pause.style.visibility = "visible";
        if (playing) {
            MIDIjs.resume();
        } else {
            MIDIjs.play(midifilename);
            playing = true;
        }
    }

    const pauseMIDIHandler = function() {
        // let midiString = 'data:audio/midi;base64,' + base64midi;
        play.style.visibility = "visible";
        pause.style.visibility = "collapse";
        MIDIjs.pause(midifilename);
    }

    const stopMIDIHandler = function() {
        var T = getMS(0);
        currenttime.innerHTML = T;
        play.style.visibility = "visible";
        pause.style.visibility = "collapse";
        MIDIjs.stop();
        playing = false;
    }

    const midiCallback = function(e) {
        // e.time, e.status
        // progress.value = e.time * 1000;
        // var T = getMS(Math.floor(e.time));
        var T = getMS(e.time);
        currenttime.innerHTML = T;
        // console.log((e.time*100) / totalDuration);
        progress.value = ((e.time*100) / totalDuration);
        // console.log(e.time, totalDuration);
        if (e.time == totalDuration) {
            stop.click();
        }
    }

    const progress = document.getElementById("progress")
    const currenttime = document.getElementById("currenttime")
    const totaltime = document.getElementById("totaltime")

    // Wire up the buttons to actually work.
    const play = document.getElementById("play")
    play.addEventListener("click", playMIDIHandler);
    const pause = document.getElementById("pause")
    pause.addEventListener("click", pauseMIDIHandler);
    const stop = document.getElementById("stop")
    stop.addEventListener("click", stopMIDIHandler);
    pause.style.visibility = "collapse";

    // Set the function as message callback
    MIDIjs.player_callback = midiCallback;

    MIDIjs.get_duration(midifilename, function(seconds) {
        console.log(seconds);
        const T = getMS(seconds);
        totalDuration = seconds;
        totaltime.innerHTML = T;
    });

</script>

</html>
