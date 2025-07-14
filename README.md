# Raffle System

A modern, responsive raffle system built with PHP and CSS. This application allows you to manage participants and conduct fair random drawings.

## Features

- **Add Participants**: Easy form to add participants with name and email
- **Participant Management**: View all participants with timestamps and remove individuals
- **Random Winner Selection**: Fair random drawing from all participants
- **Winner History**: Display the latest winner with details
- **Statistics Dashboard**: Real-time stats including total participants and win probability
- **Responsive Design**: Works perfectly on desktop, tablet, and mobile devices
- **Session Management**: Uses PHP sessions to maintain data during the session
- **Clean UI**: Modern gradient design with smooth animations

## Requirements

- PHP 7.0 or higher
- Web server (Apache, Nginx, or built-in PHP server)
- Modern web browser

## Installation

1. Clone or download the files to your web server directory
2. Ensure your web server has PHP enabled
3. Access the application through your web browser

## Usage

### For Development/Testing

You can use PHP's built-in server for quick testing:

```bash
# Navigate to the project directory
cd "c:\Users\genav\Raffle System"

# Start PHP built-in server
php -S localhost:8000
```

Then open your browser and go to `http://localhost:8000`

### For Production

1. Upload the files to your web hosting server
2. Ensure the directory has proper permissions
3. Access the application through your domain

## File Structure

```
Raffle System/
├── index.php      # Main application file
├── styles.css     # All CSS styles
└── README.md      # This documentation
```

## How to Use

1. **Add Participants**: 
   - Fill in the participant's name and email
   - Click "Add Participant"

2. **Draw Winner**:
   - Click the "🎲 Draw Winner" button
   - The system will randomly select one participant

3. **Manage Participants**:
   - View all participants in the list
   - Remove individual participants if needed
   - Clear all participants to start fresh

4. **View Statistics**:
   - See total number of participants
   - Check how many winners have been drawn
   - View the probability of winning for each participant

## Features Details

### Data Persistence
- Uses PHP sessions to store participant data
- Data persists during the browser session
- Automatically cleared when session expires

### Security Features
- Input sanitization with `htmlspecialchars()`
- Form validation on both client and server side
- CSRF protection through form structure

### Responsive Design
- Mobile-first responsive design
- Smooth animations and transitions
- Modern gradient backgrounds
- Accessible color schemes

## Customization

### Styling
Edit `styles.css` to customize:
- Colors and gradients
- Typography
- Layout and spacing
- Animations

### Functionality
Edit `index.php` to modify:
- Participant fields
- Drawing logic
- Data storage method
- Additional features

## Browser Compatibility

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Internet Explorer 11+

## Future Enhancements

Potential improvements you could add:
- Database integration for permanent storage
- Export participants to CSV
- Multiple raffle events
- Winner exclusion options
- Email notifications
- Admin authentication
- Audit trail/logging

## License

This project is open source and available under the MIT License.

## Support

For issues or questions, please check the code comments or modify the application according to your needs.
