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
    'https://generativelanguage.googleapis.com/v1beta2/models/text-bison-001:generateText',
    'https://generativelanguage.googleapis.com/v1beta2/models/chat-bison-001:generateMessage',
    'https://generativelanguage.googleapis.com/v1beta2/models/gemini-1.0:generateMessage',
]

payloads = {
    'text_direct': {'text': 'Bonjour'},
    'prompt_text': {'prompt': {'text': 'Bonjour'}},
    'prompt_simple': {'prompt': 'Bonjour'},
    'prompt_message_text': {'prompt': {'messages': [{'author': 'user', 'content': 'Bonjour'}]}},
    'prompt_message_content_text': {'prompt': {'messages': [{'author': 'user', 'content': {'text': 'Bonjour'}}]}},
    'prompt_message_content_list': {'prompt': {'messages': [{'author': 'user', 'content': [{'type': 'text', 'text': 'Bonjour'}]}]}},
    'input_text': {'instances': [{'input': 'Bonjour'}]},
}
for endpoint in endpoints:
    for name, payload in payloads.items():
        print('\nENDPOINT:', endpoint, 'PAYLOAD:', name)
        data = json.dumps(payload).encode('utf-8')
        req = urllib.request.Request(endpoint, data=data, headers=headers, method='POST')
        try:
            with urllib.request.urlopen(req, timeout=20) as res:
                body = res.read(1200).decode('utf-8', 'ignore')
                print('STATUS', res.status)
                print(body)
        except urllib.error.HTTPError as e:
            print('HTTPError', e.code)
            print(e.read(1200).decode('utf-8', 'ignore'))
        except Exception as e:
            print(type(e).__name__, e)
