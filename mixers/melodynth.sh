# Function to generate a random synth part
generate_part() {
  local part_file=$1
  local duration=$2

  # Generate random frequencies for the part
  freq1=$((RANDOM % 200 + 100))  # Random base frequency between 100-300 Hz
  freq2=$((RANDOM % 300 + 200))  # Random second frequency between 200-500 Hz
  freq3=$((RANDOM % 400 + 300))  # Random third frequency between 300-700 Hz

  # Generate random waveforms with unique frequencies for this part
  sox -n -c 2 $part_file synth $duration \
    sine ${freq1}-${freq2} \
    square ${freq2}-${freq3} \
    sawtooth ${freq3}-$(($freq3 + 200)) \
    vol 0.6 fade q 0 $duration 0.5
}

# Total duration of the melody
total_duration=4
part_duration=$((total_duration / 4))  # Split into 4 parts

# Temporary files for each part
part1="part1.wav"
part2="part2.wav"
part3="part3.wav"
part4="part4.wav"

# Generate each part
generate_part $part1 $part_duration
generate_part $part2 $part_duration
generate_part $part3 $part_duration
generate_part $part4 $part_duration

# Combine parts into a single melody
output_file="$1.wav"
sox $part1 $part2 $part3 $part4 $output_file

# Apply a random pitch shift to the entire melody
pitch_shift=$((RANDOM % 11 - 5))  # Random pitch shift between -5 and +5 semitones
sox $output_file final_melody.wav pitch $((pitch_shift * 100))

# Clean up temporary files
rm $part1 $part2 $part3 $part4

