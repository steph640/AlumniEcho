import os
import json
import urllib.request
import urllib.error
from pathlib import Path

# Load .env manually because environment may not be loaded in terminal.
env = {}
for line in Path('.env').read_text(encoding='utf-8').splitlines():
    line = line.strip()
    if not line or line.startswith('#'):
        continue
    if '=' in line:
        key, value = line.split('=', 1)
        env[key.strip()] = value.strip()

print('API key present:', 'GEMINI_API_KEY' in env and bool(env['GEMINI_API_KEY']))
key = env.get('GEMINI_API_KEY')
headers = {
    'User-Agent': 'Mozilla/5.0',
    'X-Goog-Api-Key': key,
    'Content-Type': 'application/json',
}

urls = [
    'https://generativelanguage.googleapis.com/v1beta2/models',
    'https://generativelanguage.googleapis.com/v1beta2/models?key={}'.format(key),
    'https://generativelanguage.googleapis.com/v1beta2/models?key={}&pageSize=100'.format(key),
    'https://generativelanguage.googleapis.com/v1beta2/models/gemini-1.0',
    'https://generativelanguage.googleapis.com/v1beta2/models/chat-bison-001',
    'https://generativelanguage.googleapis.com/v1beta2/models/text-bison-001',
    'https://generativelanguage.googleapis.com/v1beta2/models/gemini-1.0:generateMessage',
    'https://generativelanguage.googleapis.com/v1beta2/models/chat-bison-001:generateMessage',
    'https://generativelanguage.googleapis.com/v1beta2/models/text-bison-001:generateText',
]

payload = json.dumps({
    'prompt': {
        'messages': [
            {
                'author': 'user',
                'content': [
                    {'type': 'text', 'text': 'Bonjour'},
                ],
            },
        ],
    },
}).encode('utf-8')

for url in urls:
    print('\nURL:', url)
    method = 'POST' if 'generate' in url or 'generateText' in url else 'GET'
    data = payload if method == 'POST' else None
    req = urllib.request.Request(url, headers=headers, data=data, method=method)
    try:
        with urllib.request.urlopen(req, timeout=20) as res:
            print('Status:', res.status)
            body = res.read(800).decode('utf-8', 'ignore')
            print(body)
    except urllib.error.HTTPError as e:
        print('HTTPError', e.code)
        try:
            print(e.read(800).decode('utf-8', 'ignore'))
        except Exception as ex:
            print('Error reading body:', ex)
    except Exception as e:
        print(type(e).__name__, e)
