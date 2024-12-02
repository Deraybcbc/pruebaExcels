import pandas as pd

file_path = './usuarios.xlsx'

df = pd.read_excel(file_path)

print(df)