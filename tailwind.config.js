/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  safelist: [
    // Background colors
    'bg-emerald-50', 'bg-emerald-100', 'bg-emerald-500', 'bg-emerald-600', 'bg-emerald-700',
    'bg-teal-50', 'bg-teal-100', 'bg-teal-500', 'bg-teal-600', 'bg-teal-700',
    'bg-rose-50', 'bg-rose-100', 'bg-rose-500', 'bg-rose-600', 'bg-rose-700',
    'bg-orange-50', 'bg-orange-100', 'bg-orange-500', 'bg-orange-600', 'bg-orange-700',
    'bg-purple-50', 'bg-purple-100', 'bg-purple-500', 'bg-purple-600', 'bg-purple-700',
    'bg-cyan-50', 'bg-cyan-100', 'bg-cyan-500', 'bg-cyan-600', 'bg-cyan-700',
    'bg-pink-50', 'bg-pink-100', 'bg-pink-500', 'bg-pink-600', 'bg-pink-700',
    'bg-fuchsia-50', 'bg-fuchsia-100', 'bg-fuchsia-500', 'bg-fuchsia-600', 'bg-fuchsia-700',
    
    // Text colors
    'text-emerald-500', 'text-emerald-600', 'text-emerald-700',
    'text-teal-500', 'text-teal-600', 'text-teal-700',
    'text-rose-500', 'text-rose-600', 'text-rose-700',
    'text-orange-500', 'text-orange-600', 'text-orange-700',
    'text-purple-500', 'text-purple-600', 'text-purple-700',
    'text-cyan-500', 'text-cyan-600', 'text-cyan-700',
    'text-pink-500', 'text-pink-600', 'text-pink-700',
    'text-fuchsia-500', 'text-fuchsia-600', 'text-fuchsia-700',
    
    // Border colors
    'border-emerald-500', 'border-teal-500', 'border-rose-500', 
    'border-orange-500', 'border-purple-500', 'border-cyan-500',
    'border-pink-500', 'border-fuchsia-500',
    
    // Ring colors
    'ring-emerald-500', 'ring-teal-500', 'ring-rose-500',
    'ring-orange-500', 'ring-purple-500', 'ring-cyan-500',
    'ring-pink-500', 'ring-fuchsia-500',
    
    // Focus ring colors
    'focus:ring-emerald-500', 'focus:ring-teal-500', 'focus:ring-rose-500',
    'focus:ring-orange-500', 'focus:ring-purple-500', 'focus:ring-cyan-500',
    'focus:ring-pink-500', 'focus:ring-fuchsia-500',
    
    // Focus border colors
    'focus:border-emerald-500', 'focus:border-teal-500', 'focus:border-rose-500',
    'focus:border-orange-500', 'focus:border-purple-500', 'focus:border-cyan-500',
    'focus:border-pink-500', 'focus:border-fuchsia-500',
    
    // Gradient from colors
    'from-emerald-500', 'from-teal-500', 'from-rose-500',
    'from-orange-500', 'from-purple-500', 'from-cyan-500',
    'from-pink-500', 'from-fuchsia-500',
    
    // Gradient via colors
    'via-emerald-500', 'via-teal-500', 'via-rose-500',
    'via-orange-500', 'via-purple-500', 'via-cyan-500',
    'via-pink-500', 'via-fuchsia-500',
    
    // Gradient to colors
    'to-emerald-600', 'to-teal-600', 'to-rose-600',
    'to-orange-600', 'to-purple-600', 'to-cyan-600',
    'to-pink-600', 'to-fuchsia-600',
    
    // Hover background colors
    'hover:bg-emerald-700', 'hover:bg-teal-700', 'hover:bg-rose-700',
    'hover:bg-orange-700', 'hover:bg-purple-700', 'hover:bg-cyan-700',
    'hover:bg-pink-700', 'hover:bg-fuchsia-700',
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
