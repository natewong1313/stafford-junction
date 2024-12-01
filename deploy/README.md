to run locally, you'll want to create a .env file that contains PRIVATE_KEY="private key contents go here"
then you can use [act](https://github.com/nektos/act) to run locally

```
act push --secret-file=.env
```

test locally

```python3
python3 -m venv venv
source venv/bin/activate
pip install -r requirements.txt
python main.py
```
