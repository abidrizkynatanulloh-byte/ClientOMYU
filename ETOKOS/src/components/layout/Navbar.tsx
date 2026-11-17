"use client";

import React, { useState } from "react";
import Link from "next/link";
import { usePathname } from "next/navigation";
import { Home, Search, ShieldCheck, UserCheck, Menu, X, Building, Phone } from "lucide-react";
import { useKosStore } from "@/lib/store";

export function Navbar() {
  const pathname = usePathname();
  const { role } = useKosStore();
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  return (
    <header className="bg-white/90 backdrop-blur-md sticky top-[41px] z-30 border-b border-orange-100 shadow-sm transition">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-16">
          {/* Logo */}
          <Link href="/" className="flex items-center space-x-2.5 group">
            <div className="w-10 h-10 rounded-xl bg-gradient-to-tr from-orange-600 to-amber-500 flex items-center justify-center text-white font-black text-xl shadow-md group-hover:scale-105 transition">
              eK
            </div>
            <div>
              <span className="text-xl font-extrabold tracking-tight text-slate-900">
                eto<span className="text-orange-600">Kos</span>
              </span>
              <span className="hidden sm:inline-block ml-2 text-[10px] font-semibold bg-orange-100 text-orange-800 px-2 py-0.5 rounded-full">
                SaaS Kos Indonesia
              </span>
            </div>
          </Link>

          {/* Desktop Nav */}
          <nav className="hidden md:flex items-center space-x-6 text-sm font-medium">
            <Link
              href="/"
              className={`flex items-center space-x-1.5 transition ${
                pathname === "/" ? "text-orange-600 font-bold" : "text-slate-600 hover:text-orange-600"
              }`}
            >
              <Home className="w-4 h-4" />
              <span>Cari Kos</span>
            </Link>
            <Link
              href="/owner/dashboard"
              className={`flex items-center space-x-1.5 transition ${
                pathname.startsWith("/owner") ? "text-orange-600 font-bold" : "text-slate-600 hover:text-orange-600"
              }`}
            >
              <ShieldCheck className="w-4 h-4 text-orange-500" />
              <span>Portal Owner</span>
            </Link>
            <Link
              href="/penghuni"
              className={`flex items-center space-x-1.5 transition ${
                pathname.startsWith("/penghuni") ? "text-orange-600 font-bold" : "text-slate-600 hover:text-orange-600"
              }`}
            >
              <UserCheck className="w-4 h-4 text-sky-500" />
              <span>Portal Penghuni</span>
            </Link>
          </nav>

          {/* Action Button */}
          <div className="hidden md:flex items-center space-x-3">
            <Link
              href="/login"
              className="px-4 py-2 text-xs font-semibold rounded-lg bg-orange-600 text-white hover:bg-orange-700 shadow-sm transition"
            >
              Demo Login
            </Link>
          </div>

          {/* Mobile Hamburger */}
          <div className="flex md:hidden">
            <button
              onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
              className="p-2 rounded-lg text-slate-600 hover:bg-slate-100 focus:outline-none"
            >
              {mobileMenuOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
            </button>
          </div>
        </div>
      </div>

      {/* Mobile Drawer */}
      {mobileMenuOpen && (
        <div className="md:hidden border-t border-slate-200 bg-white px-4 pt-3 pb-5 space-y-2 shadow-lg">
          <Link
            href="/"
            onClick={() => setMobileMenuOpen(false)}
            className="flex items-center space-x-2 py-2 px-3 rounded-lg text-slate-700 hover:bg-orange-50 hover:text-orange-600 font-medium text-sm"
          >
            <Home className="w-4 h-4" />
            <span>Cari Kos (Listing Publik)</span>
          </Link>
          <Link
            href="/owner/dashboard"
            onClick={() => setMobileMenuOpen(false)}
            className="flex items-center space-x-2 py-2 px-3 rounded-lg text-slate-700 hover:bg-orange-50 hover:text-orange-600 font-medium text-sm"
          >
            <ShieldCheck className="w-4 h-4 text-orange-500" />
            <span>Portal Owner / Pengelola</span>
          </Link>
          <Link
            href="/penghuni"
            onClick={() => setMobileMenuOpen(false)}
            className="flex items-center space-x-2 py-2 px-3 rounded-lg text-slate-700 hover:bg-orange-50 hover:text-orange-600 font-medium text-sm"
          >
            <UserCheck className="w-4 h-4 text-sky-500" />
            <span>Portal Penghuni</span>
          </Link>
          <div className="pt-2">
            <Link
              href="/login"
              onClick={() => setMobileMenuOpen(false)}
              className="block w-full text-center py-2.5 text-xs font-semibold rounded-lg bg-orange-600 text-white hover:bg-orange-700 shadow-sm"
            >
              Demo Switcher / Login
            </Link>
          </div>
        </div>
      )}
    </header>
  );
}
