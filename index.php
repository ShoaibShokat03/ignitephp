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
            margin-bottom: 20px;
        }

        .note {
            font-size: 0.95rem;
            color: #aaa;
            margin-bottom: 30px;
            background-color: rgba(255, 255, 255, 0.05);
            padding: 12px;
            border-radius: 8px;
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
            margin: 5px;
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
            IgnitePHP is a blazing-fast, lightweight PHP framework built for speed, simplicity, and seamless React integration.
        </p>

        <div class="note">
            <strong>📌 Configuration Required:</strong><br>
            If you're running IgnitePHP in a subdirectory (e.g., <code>/myproject</code>), make sure to set:
            <ul style="text-align: left; margin-top: 10px;list-style:none;">
                <li><strong>1. BASE_URL</strong> in <code>config/.env</code><br>
                    Example: <code>BASE_URL="http://localhost/01/ignitephp"</code>
                </li>
                <li style="margin-top: 10px;"><strong>2. .htaccess</strong> Rewrite Paths:<br>
                    Update the following lines if project is in a subdirectory:
                    <pre>
RewriteBase http://localhost/01/ignitephp
RewriteCond %{REQUEST_URI} ^/01/ignitephp/api/
                    </pre>
                </li>
            </ul>
            🔄 These values help IgnitePHP route requests correctly in your environment.
        </div>

        <p>Try out the default APIs:</p>
        <div class="api-message">
            <p id="message">Loading API message...</p>
            <a href="api/" class="cta-button" target="_blank">Welcome API</a>
            <a href="api/hello" class="cta-button" target="_blank">Hello, World!</a>
        </div>

        <div class="tagline">Built for speed. Designed for developers.</div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch("api/hello")
                .then(response => response.json())
                .then(data => {
                    const msg = data.message || "API connected successfully!";
                    document.getElementById("message").textContent = msg;
                })
                .catch(() => {
                    document.getElementById("message").textContent = "API not responding. Check your setup.";
                });
        });
    </script>
</body>

</html>