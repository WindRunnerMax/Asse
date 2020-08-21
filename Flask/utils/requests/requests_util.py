import warnings
warnings.filterwarnings('ignore')
import requests
from requests.adapters import HTTPAdapter


def get_session():
    session = requests.Session()
    session.mount('http://', HTTPAdapter(max_retries=3))
    session.mount('https://', HTTPAdapter(max_retries=3))
    return session
