<?php
/**
 * Example theme for MarkFlat CMS
 * 
 * This theme demonstrates how to create a custom theme with a modern, vibrant style.
 * To use this theme, set MF_CMS_THEME=example in your .env file.
 */

return [
    // Page background and text
    'body' => 'bg-gradient-to-br from-indigo-50 to-pink-50 text-slate-800',
    
    // Navigation
    'nav' => 'bg-white/80 backdrop-blur-sm shadow-sm border-b border-slate-200',
    'navLink' => 'text-slate-600 hover:text-indigo-600',
    
    // Headers
    'header' => 'bg-white/50 backdrop-blur-sm shadow-sm border-b border-slate-200',
    'headerTitle' => 'text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-pink-600',
    
    // Content containers
    'container' => 'bg-white/80 backdrop-blur-sm shadow-sm border border-slate-200 rounded-xl',
    'title' => 'text-2xl font-bold text-slate-800 hover:text-indigo-600 transition-colors',
    'content' => 'prose prose-slate prose-indigo max-w-none',
    
    // Interactive elements
    'tag' => 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100 border border-indigo-200 transition-colors',
    'link' => 'text-indigo-600 hover:text-indigo-800 transition-colors',
    
    // Metadata
    'date' => 'text-slate-500',
    'views' => 'text-slate-500',
    
    // Pagination
    'pagination' => 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50 transition-colors',
    'paginationActive' => 'bg-indigo-50 border-indigo-300 text-indigo-700'
];
