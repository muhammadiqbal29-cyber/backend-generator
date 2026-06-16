<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crudaja - CRUD Boilerplate Generator</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Highlight.js for Syntax Highlighting -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/atom-one-light.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/languages/php.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/languages/go.min.js"></script>

    <style>
        :root {
            --bg-color: #f8fafc;
            --surface: rgba(255, 255, 255, 0.8);
            --surface-hover: rgba(241, 245, 249, 0.9);
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --secondary: #06b6d4;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: rgba(0, 0, 0, 0.08);
            --danger: #ef4444;
            --success: #10b981;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* Ambient Background Gradients */
        .ambient-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -1;
            overflow: hidden;
        }

        .ambient-bg::before,
        .ambient-bg::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.5;
            animation: float 10s infinite alternate;
        }

        .ambient-bg::before {
            top: -10%;
            left: -10%;
            width: 50vw;
            height: 50vw;
            background: radial-gradient(circle, var(--primary) 0%, transparent 70%);
        }

        .ambient-bg::after {
            bottom: -10%;
            right: -10%;
            width: 40vw;
            height: 40vw;
            background: radial-gradient(circle, var(--secondary) 0%, transparent 70%);
            animation-delay: -5s;
        }

        @keyframes float {
            0% {
                transform: translate(0, 0);
            }

            100% {
                transform: translate(50px, 50px);
            }
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        @media(min-width: 1024px) {
            .container {
                grid-template-columns: 400px 1fr;
            }
        }

        /* Glassmorphism Panel */
        .glass-panel {
            background: var(--surface);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .glass-panel:hover {
            box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.6);
        }

        header {
            text-align: center;
            margin-bottom: 2rem;
            padding-top: 2rem;
        }

        h1 {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(to right, #a855f7, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        h2 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text-main);
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 0.5rem;
        }

        p.subtitle {
            color: var(--text-muted);
            font-size: 1rem;
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
        }

        input[type="text"],
        select {
            width: 100%;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid var(--border-color);
            color: var(--text-main);
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            font-size: 1rem;
            outline: none;
            transition: all 0.2s ease;
        }

        input[type="text"]:focus,
        select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(139, 92, 246, 0.2);
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .checkbox-group input {
            accent-color: var(--primary);
            width: 1rem;
            height: 1rem;
            cursor: pointer;
        }

        .checkbox-group label {
            margin: 0;
            cursor: pointer;
        }

        /* Dynamic Fields List */
        .field-item {
            background: rgba(248, 250, 252, 0.6);
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
            position: relative;
        }

        .btn-remove {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background: transparent;
            color: var(--text-muted);
            border: none;
            cursor: pointer;
            font-size: 1.2rem;
            transition: color 0.2s;
        }

        .btn-remove:hover {
            color: var(--danger);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
            font-size: 1rem;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            width: 100%;
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(139, 92, 246, 0.4);
        }

        .btn-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .btn-secondary {
            background: rgba(0, 0, 0, 0.03);
            color: var(--text-main);
            border: 1px solid var(--border-color);
            width: 100%;
            margin-bottom: 1rem;
        }

        .btn-secondary:hover {
            background: rgba(0, 0, 0, 0.06);
        }

        /* Output Area */
        .output-container {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 0.5rem;
        }

        .tab {
            background: transparent;
            color: var(--text-muted);
            border: none;
            padding: 0.5rem 1rem;
            font-weight: 600;
            cursor: pointer;
            border-radius: 0.25rem;
            transition: all 0.2s;
        }

        .tab:hover {
            color: var(--text-main);
            background: rgba(0, 0, 0, 0.04);
        }

        .tab.active {
            color: var(--primary-hover);
            background: rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.3);
        }

        .sub-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .sub-tab {
            background: rgba(255, 255, 255, 0.9);
            color: var(--text-muted);
            border: 1px solid var(--border-color);
            padding: 0.25rem 0.75rem;
            font-size: 0.875rem;
            border-radius: 1rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .sub-tab.active {
            color: var(--text-main);
            border-color: var(--secondary);
            background: rgba(6, 182, 212, 0.1);
        }

        .code-window {
            background: #f1f5f9;
            border-radius: 0.5rem;
            padding: 1rem;
            overflow-x: auto;
            border: 1px solid var(--border-color);
            flex-grow: 1;
            position: relative;
        }

        .code-window pre {
            margin: 0;
            font-family: 'Fira Code', monospace;
            font-size: 0.9rem;
            line-height: 1.5;
            color: #334155;
        }

        .btn-copy {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background: rgba(0, 0, 0, 0.05);
            border: none;
            color: var(--text-main);
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            cursor: pointer;
            font-size: 0.75rem;
            transition: background 0.2s;
        }

        .btn-copy:hover {
            background: rgba(0, 0, 0, 0.1);
        }

        /* Loader */
        .loader {
            display: none;
            width: 1.5rem;
            height: 1.5rem;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        .loading .loader {
            display: inline-block;
        }

        .loading .btn-text {
            display: none;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            min-height: 300px;
            color: var(--text-muted);
            text-align: center;
        }

        .empty-state svg {
            width: 4rem;
            height: 4rem;
            opacity: 0.5;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <div class="ambient-bg"></div>

    <header>
        <h1>Backend Generator</h1>
        <p class="subtitle">Accelerate your Go & Laravel Backend Development</p>
    </header>

    <div class="container" x-data="generatorApp()">

        <!-- Sidebar Form -->
        <div class="glass-panel">
            <h2>Schema Definition</h2>

            <div class="form-group">
                <label for="resource_name">Resource Name (e.g. Product, User)</label>
                <input type="text" id="resource_name" x-model="resourceName" placeholder="Product">
            </div>

            <div style="margin-top: 2rem;">
                <label style="font-size: 1rem; font-weight: 600; color: var(--text-main);">Fields</label>

                <template x-for="(field, index) in fields" :key="index">
                    <div class="field-item">
                        <button class="btn-remove" @click="removeField(index)" title="Remove Field">&times;</button>

                        <div class="form-group">
                            <label>Field Name</label>
                            <input type="text" x-model="field.name" placeholder="price">
                        </div>

                        <div class="form-group">
                            <label>Type</label>
                            <select x-model="field.type">
                                <option value="string">String</option>
                                <option value="integer">Integer</option>
                                <option value="text">Text</option>
                                <option value="boolean">Boolean</option>
                                <option value="uuid">UUID</option>
                                <option value="date">Date / Time</option>
                            </select>
                        </div>

                        <div class="checkbox-group">
                            <input type="checkbox" :id="'nullable_'+index" x-model="field.is_nullable">
                            <label :for="'nullable_'+index">Nullable</label>

                            <span style="margin: 0 0.5rem; color: var(--text-muted);">|</span>

                            <input type="checkbox" :id="'primary_'+index" x-model="field.is_primary">
                            <label :for="'primary_'+index">Primary Key</label>
                        </div>
                    </div>
                </template>

                <button class="btn btn-secondary" @click="addField()">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Field
                </button>
            </div>

            <button class="btn btn-primary" :class="{'loading': isLoading}" @click="generateCode()"
                :disabled="isLoading || fields.length === 0 || !resourceName">
                <span class="btn-text">Generate Code</span>
                <div class="loader"></div>
            </button>
        </div>

        <!-- Output Panel -->
        <div class="glass-panel output-container">

            <template x-if="!generatedData">
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                    </svg>
                    <p>Design your schema and click Generate to see the magic.</p>
                </div>
            </template>

            <template x-if="generatedData">
                <div style="height: 100%; display: flex; flex-direction: column;">
                    <div class="tabs">
                        <button class="tab" :class="{'active': activeLang === 'laravel'}"
                            @click="activeLang = 'laravel'">Laravel</button>
                        <button class="tab" :class="{'active': activeLang === 'go'}" @click="activeLang = 'go'">Go
                            (Gin+GORM)</button>
                    </div>

                    <div class="sub-tabs">
                        <template x-for="(code, filename) in generatedData[activeLang]" :key="filename">
                            <button class="sub-tab" :class="{'active': activeFile === filename}"
                                @click="activeFile = filename" x-text="filename"></button>
                        </template>
                    </div>

                    <div class="code-window">
                        <button class="btn-copy" @click="copyCode(generatedData[activeLang][activeFile])"
                            x-text="copyText"></button>
                        <pre><code :class="activeLang === 'laravel' ? 'language-php' : 'language-go'" x-html="highlightCode(generatedData[activeLang][activeFile], activeLang)"></code></pre>
                    </div>
                </div>
            </template>

        </div>
    </div>

    <script>
        function generatorApp() {
            return {
                resourceName: 'Product',
                fields: [
                    { name: 'id', type: 'uuid', is_nullable: false, is_primary: true },
                    { name: 'name', type: 'string', is_nullable: false, is_primary: false },
                    { name: 'price', type: 'integer', is_nullable: false, is_primary: false }
                ],
                isLoading: false,
                generatedData: null,
                activeLang: 'laravel',
                activeFile: '',
                copyText: 'Copy',

                addField() {
                    this.fields.push({ name: '', type: 'string', is_nullable: false, is_primary: false });
                },

                removeField(index) {
                    this.fields.splice(index, 1);
                },

                async generateCode() {
                    this.isLoading = true;
                    try {
                        const response = await fetch('/api/generate', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                resource_name: this.resourceName,
                                fields: this.fields
                            })
                        });

                        const data = await response.json();
                        if (response.ok) {
                            this.generatedData = data;
                            this.activeLang = 'laravel';
                            this.activeFile = Object.keys(data.laravel)[0];
                        } else {
                            alert('Error: ' + (data.message || 'Validation failed'));
                        }
                    } catch (e) {
                        alert('Failed to generate code.');
                    } finally {
                        this.isLoading = false;
                    }
                },

                highlightCode(code, lang) {
                    if (!code) return '';
                    const language = lang === 'laravel' ? 'php' : 'go';
                    return hljs.highlight(code, { language }).value;
                },

                copyCode(code) {
                    navigator.clipboard.writeText(code).then(() => {
                        this.copyText = 'Copied!';
                        setTimeout(() => this.copyText = 'Copy', 2000);
                    });
                },

                init() {
                    this.$watch('activeLang', (val) => {
                        if (this.generatedData && this.generatedData[val]) {
                            this.activeFile = Object.keys(this.generatedData[val])[0];
                        }
                    });
                }
            }
        }
    </script>
</body>

</html>