import csv
import numpy as np
from scipy.stats import pearsonr, spearmanr

test_file_path = 'sts-test-100.csv'
results_file_path = 'items.csv'
output_file_path = 'correlation_results.txt'

vector1 = []
vector2 = []

with open(test_file_path, 'r', newline='') as file1:
    csv_reader = csv.reader(file1, delimiter='\t')
    for row in csv_reader:
        vector1.append(float(row[4]))

with open(results_file_path, 'r', newline='') as file2:
    csv_reader = csv.reader(file2, delimiter=',')
    for row in csv_reader:
        vector2.append(float(row[3]))

# Calculate correlation coefficients
pearson_corr, _ = pearsonr(vector1, vector2)
spearman_corr, _ = spearmanr(vector1, vector2)

# Save the correlation coefficients to a text file
with open(output_file_path, 'w') as output_file:
    output_file.write(f"Test vector: {vector1}\n\n")
    output_file.write(f"Result vector: {vector2}\n\n")
    output_file.write(f"Pearson's correlation coefficient: {pearson_corr}\n\n")
    output_file.write(f"Spearman's correlation coefficient: {spearman_corr}")

print("Correlation coefficients saved to:", output_file_path)
