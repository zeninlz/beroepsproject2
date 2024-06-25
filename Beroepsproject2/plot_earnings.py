import matplotlib.pyplot as plt

# Read the total earnings from the text file
with open("total_earnings.txt", "r") as file:
    total_earnings = float(file.read())

# Plot the total earnings
plt.plot([total_earnings], marker='o', linestyle='-')
plt.title("Total Earnings")
plt.xlabel("Time")
plt.ylabel("Earnings ($)")
plt.grid(True)

# Save the plot as an image
plt.savefig("total_earnings_plot.png")
