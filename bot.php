<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AFRICA AI BOT</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- ResponsiveVoice script for mobile text-to-speech -->
    <script src="https://code.responsivevoice.org/responsivevoice.js"></script>
</head>
<body>
    <div class="wrapper">
        <div class="title">AFRICA AI BOT</div>
        <div class="form">
            <div class="bot-inbox inbox">
                <div class="icon" style="background-color: transparent;">
                    <img src="robot-solid.svg" alt="Robot Icon" class="shake-animation" />
                </div>
                <div class="msg-header">
                    <p>Hello there, how can I help you?</p>
                </div>
            </div>
        </div>
        <div class="typing-field">
            <div class="input-data">
                <button id="mic-btn"><img id="mic-icon" src="microphone-lines-solid.svg" alt="Microphone Icon"></button>
                <input id="data" type="text" placeholder="Type something here.." required>
                <button id="send-btn" onclick="sendMessage()"><img src="paper-plane-solid.svg" alt="Send Icon" /></button>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            // Function to handle speech recognition
            function startSpeechRecognition() {
                const recognition = new webkitSpeechRecognition();
                recognition.lang = 'en-US';
                recognition.start();

                recognition.onresult = function(event) {
                    const transcript = event.results[0][0].transcript;
                    $("#data").val(transcript);
                    recognition.stop();

                    // Automatically send the text to the bot after voice recognition
                    sendMessage(transcript);
                };

                recognition.onerror = function(event) {
                    console.error('Speech recognition error:', event.error);
                    recognition.stop();
                };
            }

            function speakBotResponse(response) {
                // Check if ResponsiveVoice is available
                if (window.responsiveVoice) {
                    // Use ResponsiveVoice for text-to-speech on mobile with adjusted rate (speed)
                    window.responsiveVoice.speak(response, "UK English Female", { rate: 1.1 });
                } else {
                    // Fallback to SpeechSynthesisUtterance for browsers that support it
                    const synth = window.speechSynthesis;
                    const utterance = new SpeechSynthesisUtterance(response);

                    // Adjust the rate (speed) of the voice
                    utterance.rate = 1.2; // You can adjust this value to increase or decrease the speed

                    // Event listener for when the speech ends
                    utterance.addEventListener('end', function () {
                        $(".shake-animation").removeClass("shake");
                        $("#mic-btn img").attr("src", "microphone-lines-solid.svg"); // Set the microphone icon back to normal
                    });

                    // Start the voice speaking
                    synth.speak(utterance);
                }
            }

            // Microphone button click event
            $("#mic-btn").on("click", function(){
                startSpeechRecognition();
                $(".shake-animation").addClass("shake");
                $("#mic-btn img").attr("src", "microphone-slash-solid.svg"); // Set the microphone icon to the muted state
            });

            // Send button click event
            $("#send-btn").on("click", function(){
                const textValue = $("#data").val();
                sendMessage(textValue);
            });

            // Function to send message to the bot
            function sendMessage(message) {
                $msg = '<div class="user-inbox inbox"><div class="msg-header"><p>'+ message +'</p></div></div>';
                $(".form").append($msg);
                $("#data").val('');

                // start ajax code
                $.ajax({
                    url: 'message.php',
                    type: 'POST',
                    data: 'text='+ message,
                    success: function(result){
                        $replay = '<div class="bot-inbox inbox"><div class="icon" style="background-color: transparent;"><img src="robot-solid.svg" alt="Robot Icon" class="shake-animation" /></div><div class="msg-header"><p>'+ result +'</p></div></div>';
                        $(".form").append($replay);
                        // when chat goes down the scroll bar automatically comes to the bottom
                        $(".form").scrollTop($(".form")[0].scrollHeight);

                        // Automatically speak the bot's response with the same duration as the animation
                        speakBotResponse(result);
                    }
                });
            }

            // Initial message to speech when the page loads
            const initialBotMessage = 'Hello there, hope you are doing great! let me know if you want any help to listen to latest news, have access to your company information?';
            speakBotResponse(initialBotMessage);
        });
    </script>
</body>
</html>
