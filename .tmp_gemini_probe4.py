import json
import urllib.request
import urllib.error
from pathlib import Path

# Load .env manually
env = {}
for line in Path('.env').read_text(encoding='utf-8').splitlines():
    line=line.strip()
    if not line or line.startswith('#'):
        continue
    if '=' in line:
        key, value = line.split('=', 1)
        env[key.strip()] = value.strip()

key = env.get('GEMINI_API_KEY')
print('API key present:', bool(key))
headers = {
    'User-Agent': 'Mozilla/5.0',
    'X-Goog-Api-Key': key,
    'Content-Type': 'application/json',
}

payloads = {
    'content_string': {'prompt': {'messages': [{'author': 'user', 'content': 'Bonjour'}]}},
    'prompt_string': {'prompt': 'Bonjour'},
    'message_text': {'prompt': {'messages': [{'author': 'user', 'text': 'Bonjour'}]}},
}
urls = [
    'https://generativelanguage.googleapis.com/v1beta2/models/gemini-1.0:generateMessage',
    'https://generativelanguage.googleapis.com/v1beta2/models/gemini-1.0:generateMessage?key={}'.format(key),
]
for url in urls:
    for name, payload in payloads.items():
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
