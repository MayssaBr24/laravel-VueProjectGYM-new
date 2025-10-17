export const formatDate = (dateString) => {
  if (!dateString) return 'Date non disponible';
  const options = { 
    weekday: 'long', 
    day: 'numeric', 
    month: 'long',
    year: 'numeric'
  };
  return new Date(dateString).toLocaleDateString('fr-FR', options);
};

export const formatTime = (dateString) => {
  if (!dateString) return '--:--';
  const options = { hour: '2-digit', minute: '2-digit' };
  return new Date(dateString).toLocaleTimeString('fr-FR', options);
};