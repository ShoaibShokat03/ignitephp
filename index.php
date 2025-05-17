<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>IgnitePHP - Welcome</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            color: #f9f9f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            padding: 40px;
            border-radius: 16px;
            max-width: 700px;
            width: 100%;
            text-align: center;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3);
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #fff;
        }

        p {
            font-size: 1.15rem;
            line-height: 1.7;
            color: #dcdcdc;
            margin-bottom: 30px;
        }

        .cta-button {
            background-color: #ff6a00;
            color: #fff;
            padding: 14px 28px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .cta-button:hover {
            background-color: #e65c00;
        }

        .tagline {
            margin-top: 10px;
            font-style: italic;
            color: #bbb;
        }

        @media (max-width: 600px) {
            h1 {
                font-size: 2rem;
            }

            p {
                font-size: 1rem;
            }

            .cta-button {
                width: 100%;
                padding: 12px 0;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Welcome to IgnitePHP</h1>
        <p>
            IgnitePHP is a blazing-fast, lightweight PHP framework designed for developers who value speed, simplicity, and scalability.<br />
            Get started building APIs and web applications with expressive routing, powerful architecture, and zero bloat.
        </p>
        <div class="api-message">
            <p id="message">Loading api message...</p>
            <p>
                API: <a href="api/" class="cta-button" target="_blank">Welocm Message</a>
                API: <a href="api/hello" class="cta-button" target="_blank">Hello, World!</a>
            </p>
        </div>
        <div class="tagline">Built for speed. Designed for developers.</div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch("api/hello")
                .then(response => response.json())
                .then(data => document.getElementById("message").textContent = data.message);
        });
    </script>
</body>

</html>