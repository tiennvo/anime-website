<?php
// Tạo một file PHP để quản lý chatbot.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot</title>
    <style>
        /* Phần CSS cho chatbot */
        .chat-popup {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 300px;
            height: 400px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            display: none;
            flex-direction: column;
            z-index: 9999;
        }

        .chat-popup .header {
            background-color: #0078d4;
            color: white;
            padding: 10px;
            border-radius: 10px 10px 0 0;
            font-size: 18px;
            text-align: center;
        }

        .chat-popup .messages {
            flex-grow: 1;
            padding: 10px;
            overflow-y: auto;
            border-bottom: 1px solid #ddd;
        }

        .chat-popup .input-container {
            display: flex;
            padding: 10px;
        }

        .chat-popup input[type="text"] {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .chat-popup button {
            padding: 10px 15px;
            margin-left: 10px;
            background-color: #0078d4;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .chat-popup button:hover {
            background-color: #005bb5;
        }

        /* Nút mở chatbot */
        #open-chat-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #0078d4;
            color: white;
            padding: 15px;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            z-index: 10000;
        }

        .message {
            margin-bottom: 10px;
        }

        .user-message {
            text-align: right;
            background-color: #d1f0ff;
            padding: 10px;
            border-radius: 5px;
        }

        .bot-message {
            text-align: left;
            background-color: #f1f1f1;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <!-- Nút mở chatbot -->
    <button id="open-chat-btn">💬</button>

    <!-- Khung chat popup -->
    <div id="chat-popup" class="chat-popup">
        <div class="header">Chatbot</div>
        <div id="messages" class="messages">
            <!-- Tin nhắn sẽ được thêm vào đây -->
        </div>
        <div class="input-container">
            <input type="text" id="user-input" placeholder="Type a message...">
            <button id="send-btn">Send</button>
        </div>
    </div>

    <script>
        // Lấy các phần tử
        const chatPopup = document.getElementById("chat-popup");
        const openChatBtn = document.getElementById("open-chat-btn");
        const sendBtn = document.getElementById("send-btn");
        const userInput = document.getElementById("user-input");
        const messages = document.getElementById("messages");

        // Mở và đóng chat
        openChatBtn.addEventListener("click", () => {
            chatPopup.style.display = (chatPopup.style.display === "none" || chatPopup.style.display === "") ? "flex" : "none";
        });

        // Xử lý gửi tin nhắn
        sendBtn.addEventListener("click", () => {
            const userMessage = userInput.value.trim();
            if (userMessage) {
                // Hiển thị tin nhắn của người dùng
                addMessage(userMessage, "user-message");

                // Gửi tin nhắn và nhận phản hồi từ chatbot
                const botResponse = getBotResponse(userMessage);
                addMessage(botResponse, "bot-message");

                // Xóa input sau khi gửi
                userInput.value = "";
            }
        });

        // Hàm thêm tin nhắn vào giao diện
        function addMessage(message, className) {
            const messageElement = document.createElement("div");
            messageElement.classList.add("message", className);
            messageElement.innerText = message;
            messages.appendChild(messageElement);
            messages.scrollTop = messages.scrollHeight;  // Cuộn xuống dưới cùng
        }

        // Hàm tạo phản hồi từ chatbot
        function getBotResponse(message) {
            let response = "I'm sorry, I don't understand that.";
            if (message.toLowerCase().includes("hello")) {
                response = "Hello! How can I assist you today?";
            } else if (message.toLowerCase().includes("how are you")) {
                response = "I'm doing great, thank you for asking!";
            } else if (message.toLowerCase().includes("bye")) {
                response = "Goodbye! Have a nice day!";
            }
            return response;
        }
    </script>
</body>
</html>
