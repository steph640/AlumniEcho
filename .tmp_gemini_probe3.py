import os
import json
import urllib.request
import urllib.error
from pathlib import Path

env = {}
for line in Path('.env').read_text(encoding='utf-8').splitlines():
    line=line.strip()
    if not line or line.startswith('#'): continue
    if '=' in line:
        k,v=line.split('=',1)
        env[k.strip()]=v.strip()
key=env.get('GEMINI_API_KEY')
headers = {
    'User-Agent': 'Mozilla/5.0',
    'X-Goog-Api-Key': key,
    'Content-Type': 'application/json',
}
urls = [
    'https://generativelanguage.googleapis.com/v1beta2/models/gemini-1.0:generateMessage',
    'https://generativelanguage.googleapis.com/v1beta2/models/chat-bison-001:generateMessage',
]
payloads = {
    'content_string': {'prompt': {'messages': [{'author':'user','content':'Bonjour'}]}},
    'text_field': {'prompt': {'messages': [{'author':'user','text': {'text':'Bonjour'}}]}} ,
    'content_text': {'prompt': {'messages': [{'author':'user','content': {'text':'Bonjour'}}]}},
    'content_array_text': {'prompt': {'messages': [{'author':'user','content': [{'type': 'text','text': 'Bonjour'}]}]}},
    'text_simple': {'prompt': {'messages': [{'author':'user','text': 'Bonjour'}]}},
    'prompt': {'prompt': 'Bonjour'},
}
for url in urls:
    for name,payload in payloads.items():
        print('\nURL:', url, 'PAYLOAD:', name)
        data = json.dumps(payload).encode('utf-8')
        req = urllib.request.Request(url, headers=headers, data=data, method='POST')
        try:
            with urllib.request.urlopen(req, timeout=20) as res:
                print('Status:', res.status)
                print(res.read(1200).decode('utf-8','ignore'))
        except urllib.error.HTTPError as e:
            print('HTTPError', e.code)
            print(e.read(1200).decode('utf-8','ignore'))
        except Exception as e:
            print(type(e).__name__, e)
