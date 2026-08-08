import os
import json
import urllib.request
import urllib.error
from pathlib import Path

# Load .env manually
env = {}
for line in Path('.env').read_text(encoding='utf-8').splitlines():
    line = line.strip()
    if not line or line.startswith('#'):
        continue
    if '=' in line:
        k, v = line.split('=', 1)
        env[k.strip()] = v.strip()

key = env.get('GEMINI_API_KEY')
print('API key present:', bool(key))
headers = {
    'User-Agent': 'Mozilla/5.0',
    'X-Goog-Api-Key': key,
    'Content-Type': 'application/json',
}

payloads = {
    'content_object': json.dumps({'prompt': {'messages': [{'author': 'user', 'content': {'text': 'Bonjour'}}]}}).encode('utf-8'),
    'content_string': json.dumps({'prompt': {'messages': [{'author': 'user', 'content': 'Bonjour'}]}}).encode('utf-8'),
    'instances': json.dumps({'instances': [{'input': 'Bonjour'}]}).encode('utf-8'),
}

urls = [
    'https://generativelanguage.googleapis.com/v1beta2/models/chat-bison-001:generateMessage',
    'https://generativelanguage.googleapis.com/v1beta2/models/text-bison-001:generateText',
]

for url in urls:
    for name, payload in payloads.items():
        print('\nURL:', url, 'payload:', name)
        req = urllib.request.Request(url, headers=headers, data=payload, method='POST')
        try:
            with urllib.request.urlopen(req, timeout=20) as res:
                print('Status:', res.status)
                print(res.read(1000).decode('utf-8', 'ignore'))
        except urllib.error.HTTPError as e:
            print('HTTPError', e.code)
            print(e.read(1000).decode('utf-8', 'ignore'))
        except Exception as e:
            print(type(e).__name__, e)
