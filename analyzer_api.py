from flask import Flask, request, jsonify
from transformers import pipeline

app = Flask(__name__)

# Load summarization model from Hugging Face
summarizer = pipeline("summarization")

@app.route('/analyze', methods=['POST'])
def analyze():
    data = request.json
    bill_text = data.get('text')
    
    # Validate input
    if not bill_text:
        return jsonify({"error": "No bill text provided"}), 400
    
    # Summarize the bill text
    summary = summarizer(bill_text, max_length=150, min_length=50, do_sample=False)
    summary_text = summary[0]['summary_text']
    
    return jsonify({"summary": summary_text})

if __name__ == "__main__":
    app.run(port=5000)
