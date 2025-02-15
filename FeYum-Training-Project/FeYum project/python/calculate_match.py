import sys
import json
import os
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity

def cosine_similarity_text(text1, text2):
    """Calculate cosine similarity between two pieces of text."""
    if not text1 or not text2:
        return 0.0
    vectorizer = TfidfVectorizer()
    vectors = vectorizer.fit_transform([text1, text2])
    return cosine_similarity(vectors[0:1], vectors[1:2])[0][0]

def calculate_match(job, application):
    """Calculate the percentage match between a job and an application."""
    weights = {
        "skills": 0.4,
        "languages": 0.2,
        "education": 0.2,
        "experience": 0.2
    }
    
    match_score = 0
    match_score += cosine_similarity_text(job["skills"], application["skills"]) * weights["skills"]
    match_score += cosine_similarity_text(job["languages"], application["languages"]) * weights["languages"]
    match_score += cosine_similarity_text(job["education"], application["education_summary"]) * weights["education"]
    match_score += cosine_similarity_text(job["experience"], application["experience_summary"]) * weights["experience"]

    return round(match_score * 100, 2)

def validate_input(data):
    """Validate the input JSON for required fields."""
    if "job" not in data or "applications" not in data:  # Changed from 'jobs' to 'job'
        raise ValueError("Missing 'job' or 'applications' key in the input JSON.")
    if not data["applications"]:
        raise ValueError("No applications found in the input JSON.")

    job = data["job"]  # Single job instead of multiple jobs
    applications = data["applications"]

    required_job_fields = ["skills", "languages", "education", "experience"]
    for field in required_job_fields:
        if field not in job or not job[field]:
            raise ValueError(f"Missing or empty '{field}' in job data.")

    for app in applications:
        required_app_fields = ["skills", "languages", "education_summary", "experience_summary"]
        for field in required_app_fields:
            if field not in app or not app[field]:
                raise ValueError(f"Missing or empty '{field}' in application ID {app.get('application_id', 'unknown')}.")

def main():
    if len(sys.argv) < 3:
        print("Usage: python calculate_match.py <input_file> <output_file>")
        sys.exit(1)

    input_file = sys.argv[1]
    output_file = sys.argv[2]

    if not os.path.exists(input_file):
        print(f"Error: Input file '{input_file}' does not exist.")
        sys.exit(1)

    try:
        with open(input_file, "r", encoding="utf-8") as f:
            data = json.load(f)
    except json.JSONDecodeError as e:
        print(f"Error: Invalid JSON format in '{input_file}'.")
        print(e)
        sys.exit(1)

    try:
        validate_input(data)

        job = data["job"]  # Single job instead of multiple jobs
        applications = data["applications"]

        results = []
        for application in applications:
            # Only process applications that match the job ID
            # if application["job_id"] == job["job_id"]:
                match = calculate_match(job, application)
                results.append({
                    "Id_number": application["Id_number"],
                    "application_id": application["application_id"],
                    "full_name": application["full_name"],
                    "job_title": application.get("job_title", job.get("title", "غير محدد")),
                    "match_percentage": match
                })

        # Write the results to the output file
        with open(output_file, "w", encoding="utf-8") as f:
            json.dump(results, f, ensure_ascii=False, indent=4)

        print(f"Results written to '{output_file}' successfully.")
    except ValueError as e:
        print(f"Error: {e}")
        sys.exit(1)

if __name__ == "__main__":
    main()