<?php
session_start();

// Initialize participants array if not exists
if (!isset($_SESSION['participants'])) {
    $_SESSION['participants'] = [];
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_participant':
                $name = trim($_POST['name']);
                $email = trim($_POST['email']);
                
                if (!empty($name) && !empty($email)) {
                    $participant = [
                        'id' => uniqid(),
                        'name' => htmlspecialchars($name),
                        'email' => htmlspecialchars($email),
                        'entry_time' => date('Y-m-d H:i:s')
                    ];
                    $_SESSION['participants'][] = $participant;
                    $success_message = "Participant added successfully!";
                } else {
                    $error_message = "Please fill in all fields.";
                }
                break;
                
            case 'draw_winner':
                if (!empty($_SESSION['participants'])) {
                    // If winner_id is provided from the wheel, use that participant
                    if (isset($_POST['winner_id'])) {
                        $winner_id = $_POST['winner_id'];
                        foreach ($_SESSION['participants'] as $participant) {
                            if ($participant['id'] === $winner_id) {
                                $_SESSION['last_winner'] = $participant;
                                $winner_message = "Winner drawn successfully!";
                                break;
                            }
                        }
                    } else {
                        // Fallback to random selection
                        $winner_index = array_rand($_SESSION['participants']);
                        $_SESSION['last_winner'] = $_SESSION['participants'][$winner_index];
                        $winner_message = "Winner drawn successfully!";
                    }
                } else {
                    $error_message = "No participants available for the draw.";
                }
                break;
                
            case 'clear_all':
                $_SESSION['participants'] = [];
                unset($_SESSION['last_winner']);
                $success_message = "All participants cleared.";
                break;
                
            case 'remove_participant':
                $participant_id = $_POST['participant_id'];
                $_SESSION['participants'] = array_filter($_SESSION['participants'], function($p) use ($participant_id) {
                    return $p['id'] !== $participant_id;
                });
                $_SESSION['participants'] = array_values($_SESSION['participants']); // Re-index array
                $success_message = "Participant removed successfully.";
                break;
        }
    }
}

$participants = $_SESSION['participants'] ?? [];
$last_winner = $_SESSION['last_winner'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raffle System</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <header>
            <h1>🎉 Raffle System</h1>
            <p>Add participants and draw winners fairly!</p>
        </header>

        <!-- Messages -->
        <?php if (isset($success_message)): ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <?php if (isset($winner_message)): ?>
            <div class="message winner"><?php echo $winner_message; ?></div>
        <?php endif; ?>

        <!-- Add Participant Form -->
        <div class="card">
            <h2>Add New Participant</h2>
            <form method="POST" class="participant-form">
                <input type="hidden" name="action" value="add_participant">
                <div class="form-group">
                    <label for="name">Full Name:</label>
                    <input type="text" id="name" name="name" required placeholder="Enter participant's name">
                </div>
                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" required placeholder="Enter participant's email">
                </div>
                <button type="submit" class="btn btn-primary">Add Participant</button>
            </form>
        </div>

        <!-- Participants List -->
        <div class="card">
            <div class="card-header">
                <h2>Participants (<?php echo count($participants); ?>)</h2>
                <div class="action-buttons">
                    <?php if (!empty($participants)): ?>
                        <button type="button" class="btn btn-success" onclick="startWheelSpin()">🎲 Draw Winner</button>
                        <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to clear all participants?');">
                            <input type="hidden" name="action" value="clear_all">
                            <button type="submit" class="btn btn-danger">🗑️ Clear All</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (empty($participants)): ?>
                <div class="empty-state">
                    <p>No participants yet. Add some participants to start the raffle!</p>
                </div>
            <?php else: ?>
                <div class="participants-list">
                    <?php foreach ($participants as $index => $participant): ?>
                        <div class="participant-item">
                            <div class="participant-info">
                                <span class="participant-number">#<?php echo $index + 1; ?></span>
                                <div class="participant-details">
                                    <strong><?php echo $participant['name']; ?></strong>
                                    <span class="participant-email"><?php echo $participant['email']; ?></span>
                                    <small class="participant-time">Added: <?php echo $participant['entry_time']; ?></small>
                                </div>
                            </div>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="remove_participant">
                                <input type="hidden" name="participant_id" value="<?php echo $participant['id']; ?>">
                                <button type="submit" class="btn btn-small btn-danger" onclick="return confirm('Remove this participant?');">Remove</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Winner Display -->
        <?php if ($last_winner): ?>
            <div class="card winner-card">
                <h2>🏆 Latest Winner</h2>
                <div class="winner-display">
                    <div class="winner-info">
                        <h3><?php echo $last_winner['name']; ?></h3>
                        <p><?php echo $last_winner['email']; ?></p>
                        <small>Won on: <?php echo date('Y-m-d H:i:s'); ?></small>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Spinning Wheel Modal -->
        <div id="wheelModal" class="wheel-modal">
            <div class="wheel-container">
                <div class="wheel-header">
                    <h2>🎡 Drawing Winner...</h2>
                    <p>Good luck everyone!</p>
                </div>
                <div class="wheel-wrapper">
                    <div class="wheel" id="spinningWheel">
                        <div class="wheel-center">
                            <div class="wheel-pointer"></div>
                        </div>
                        <div class="wheel-segments" id="wheelSegments"></div>
                    </div>
                </div>
                <div class="wheel-result" id="wheelResult" style="display: none;">
                    <h3>🎉 Congratulations!</h3>
                    <div class="winner-announcement" id="winnerName"></div>
                    <button class="btn btn-primary" onclick="confirmWinner()">Confirm Winner</button>
                    <button class="btn btn-secondary" onclick="closeWheel()">Draw Again</button>
                </div>
            </div>
        </div>

        <!-- Hidden form for actual winner submission -->
        <form id="winnerForm" method="POST" style="display: none;">
            <input type="hidden" name="action" value="draw_winner">
            <input type="hidden" name="winner_id" id="selectedWinnerId">
        </form>

        <!-- Statistics -->
        <div class="card">
            <h2>📊 Statistics</h2>
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-number"><?php echo count($participants); ?></span>
                    <span class="stat-label">Total Participants</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo $last_winner ? 1 : 0; ?></span>
                    <span class="stat-label">Winners Drawn</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo count($participants) > 0 ? number_format(1 / count($participants) * 100, 2) : 0; ?>%</span>
                    <span class="stat-label">Win Probability</span>
                </div>
            </div>
        </div>

        <footer>
            <p>&copy; 2025 Raffle System. Built with PHP & CSS.</p>
        </footer>
    </div>

    <script>
        // Participants data for the wheel
        const participants = <?php echo json_encode($participants); ?>;
        let selectedWinner = null;

        // Auto-hide messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const messages = document.querySelectorAll('.message');
            messages.forEach(function(message) {
                setTimeout(function() {
                    message.style.opacity = '0';
                    setTimeout(function() {
                        message.style.display = 'none';
                    }, 300);
                }, 5000);
            });
        });

        // Form validation
        document.querySelector('.participant-form').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            
            if (!name || !email) {
                e.preventDefault();
                alert('Please fill in all fields.');
            }
        });

        // Spinning wheel functionality
        function startWheelSpin() {
            if (participants.length === 0) {
                alert('No participants available for the draw.');
                return;
            }

            // Show the wheel modal
            document.getElementById('wheelModal').style.display = 'flex';
            
            // Generate wheel segments
            generateWheelSegments();
            
            // Start spinning after a short delay
            setTimeout(() => {
                spinWheel();
            }, 500);
        }

        function generateWheelSegments() {
            const wheelSegments = document.getElementById('wheelSegments');
            const segmentAngle = 360 / participants.length;
            
            wheelSegments.innerHTML = '';
            
            participants.forEach((participant, index) => {
                const segment = document.createElement('div');
                segment.className = 'wheel-segment';
                segment.style.transform = `rotate(${index * segmentAngle}deg)`;
                segment.style.background = `hsl(${(index * 360) / participants.length}, 70%, 60%)`;
                
                const segmentText = document.createElement('div');
                segmentText.className = 'segment-text';
                segmentText.textContent = participant.name;
                segmentText.style.transform = `rotate(${segmentAngle / 2}deg)`;
                
                segment.appendChild(segmentText);
                wheelSegments.appendChild(segment);
            });
        }

        function spinWheel() {
            const wheel = document.getElementById('spinningWheel');
            const winnerIndex = Math.floor(Math.random() * participants.length);
            const segmentAngle = 360 / participants.length;
            
            // Calculate the angle to land on the winner
            const winnerAngle = winnerIndex * segmentAngle;
            const extraSpins = 5; // Number of full rotations
            const finalAngle = (extraSpins * 360) + (360 - winnerAngle) + (segmentAngle / 2);
            
            // Apply the spinning animation
            wheel.style.transition = 'transform 4s cubic-bezier(0.23, 1, 0.32, 1)';
            wheel.style.transform = `rotate(${finalAngle}deg)`;
            
            // Set the selected winner
            selectedWinner = participants[winnerIndex];
            
            // Show result after animation
            setTimeout(() => {
                showWinnerResult();
            }, 4200);
        }

        function showWinnerResult() {
            const wheelResult = document.getElementById('wheelResult');
            const winnerName = document.getElementById('winnerName');
            
            winnerName.innerHTML = `
                <div class="winner-name">${selectedWinner.name}</div>
                <div class="winner-email">${selectedWinner.email}</div>
            `;
            
            wheelResult.style.display = 'block';
            
            // Add confetti effect
            createConfetti();
        }

        function confirmWinner() {
            // Set the winner ID and submit the form
            document.getElementById('selectedWinnerId').value = selectedWinner.id;
            document.getElementById('winnerForm').submit();
        }

        function closeWheel() {
            const modal = document.getElementById('wheelModal');
            const wheel = document.getElementById('spinningWheel');
            const result = document.getElementById('wheelResult');
            
            modal.style.display = 'none';
            wheel.style.transition = 'none';
            wheel.style.transform = 'rotate(0deg)';
            result.style.display = 'none';
            selectedWinner = null;
        }

        function createConfetti() {
            const colors = ['#ff6b6b', '#4ecdc4', '#45b7d1', '#f9ca24', '#f0932b', '#eb4d4b', '#6c5ce7'];
            
            for (let i = 0; i < 50; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.className = 'confetti';
                    confetti.style.left = Math.random() * 100 + '%';
                    confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.animationDelay = Math.random() * 3 + 's';
                    document.body.appendChild(confetti);
                    
                    setTimeout(() => {
                        confetti.remove();
                    }, 3000);
                }, i * 50);
            }
        }
    </script>
</body>
</html>
