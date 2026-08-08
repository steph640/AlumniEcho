import json
import urllib.request
import urllib.error
from pathlib import Path

env = {}
for line in Path('.env').read_text(encoding='utf-8').splitlines():
    line=line.strip()
    if not line or line.startswith('#'):
        continue
    if '=' in line:
        k,v=line.split('=',1)
        env[k.strip()] = v.strip()
key = env.get('GEMINI_API_KEY')
headers = {
    'User-Agent': 'Mozilla/5.0',
    'X-Goog-Api-Key': key,
    'Content-Type': 'application/json',
}

endpoints = [
    ('chat-bison-001:generateMessage', 'message', {
        'prompt': {
            'messages': [
                {'author': 'user', 'content': {'text': 'Bonjour'}}
            ]
        }
    }),
    ('chat-bison-001:generateMessage', 'message-old', {
        'prompt': {
            'messages': [
                {'author': 'user', 'content': [{'type': 'text', 'text': 'Bonjour'}]}
            ]
        }
    }),
    ('text-bison-001:generateText', 'text', {
        'prompt': {'text': 'Bonjour'}
    }),
    ('text-bison-001:generateText', 'text-old', {
        'prompt': {'text': {'text': 'Bonjour'}}
    }),
]
base = 'https://generativelanguage.googleapis.com/v1beta2/models/'
for model, name, payload in endpoints:
    url = base + model
    print('\nURL:', url, 'PAYLOAD:', name)
    data = json.dumps(payload).encode('utf-8')
    req = urllib.request.Request(url, data=data, headers=headers, method='POST')
    try:
        with urllib.request.urlopen(req, timeout=20) as res:
            print('STATUS', res.status)
            print(res.read(1200).decode('utf-8', 'ignore'))
    except urllib.error.HTTPError as e:
        print('HTTPError', e.code)
        print(e.read(1200).decode('utf-8', 'ignore'))
    except Exception as e:
        print(type(e).__name__, e)
