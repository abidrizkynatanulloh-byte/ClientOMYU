import React from "react";
import Link from "next/link";
import { Heart, Building2, Phone, Mail, ShieldCheck } from "lucide-react";

export function Footer() {
  return (
    <footer className="bg-slate-900 text-slate-300 pt-12 pb-8 border-t border-slate-800 text-sm">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
          {/* Brand */}
          <div className="space-y-3">
            <div className="flex items-center space-x-2">
              <div className="w-8 h-8 rounded-lg bg-orange-600 flex items-center justify-center text-white font-black text-lg">
                eK
              </div>
              <span className="text-xl font-bold text-white tracking-tight">
                eto<span className="text-orange-500">Kos</span>
              </span>
            </div>
            <p className="text-xs text-slate-400 leading-relaxed">
              Sistem manajemen kos-kosan & properti sewa modern terlengkap untuk pasar Indonesia. Dari pemasaran kamar, tagihan otomatis, hingga akuntansi laba rugi.
            </p>
          </div>

          {/* Quick Links */}
          <div>
            <h4 className="text-xs font-bold uppercase text-white tracking-wider mb-3">
              Fitur Utama
            </h4>
            <ul className="space-y-2 text-xs text-slate-400">
              <li>Listing Kos & Booking Online</li>
              <li>Visual Denah Kamar & Okupansi</li>
              <li>Tagihan Massal & Denda Otomatis</li>
              <li>Tiket Keluhan & Penugasan Teknisi</li>
              <li>Laporan Keuangan & Arus Kas</li>
            </ul>
          </div>

          {/* Portals */}
          <div>
            <h4 className="text-xs font-bold uppercase text-white tracking-wider mb-3">
              Portal Akses Demo
            </h4>
            <ul className="space-y-2 text-xs text-slate-400">
              <li>
                <Link href="/owner/dashboard" className="hover:text-orange-400 transition">
                  Portal Owner / Pengelola
                </Link>
              </li>
              <li>
                <Link href="/penghuni" className="hover:text-orange-400 transition">
                  Portal Penghuni (Tenant)
                </Link>
              </li>
              <li>
                <Link href="/login" className="hover:text-orange-400 transition">
                  Halaman Demo Switcher
                </Link>
              </li>
              <li>
                <Link href="/owner/aktivitas" className="hover:text-orange-400 transition">
                  Audit Trail & Log Aktivitas
                </Link>
              </li>
            </ul>
          </div>

          {/* Contact */}
          <div>
            <h4 className="text-xs font-bold uppercase text-white tracking-wider mb-3">
              Dukungan Demo
            </h4>
            <div className="space-y-2 text-xs text-slate-400">
              <p className="flex items-center space-x-1.5">
                <Phone className="w-3.5 h-3.5 text-orange-400" />
                <span>+62 812-9876-5432 (WhatsApp)</span>
              </p>
              <p className="flex items-center space-x-1.5">
                <Mail className="w-3.5 h-3.5 text-orange-400" />
                <span>halo@etokos.id</span>
              </p>
              <p className="text-[11px] text-slate-500 pt-2">
                Dirancang untuk pemilik kos Indonesia (Jabodetabek, Yogyakarta, Bandung, Surabaya, dll).
              </p>
            </div>
          </div>
        </div>

        <div className="pt-8 mt-8 border-t border-slate-800 text-center text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-2">
          <p>© {new Date().getFullYear()} etoKos Property Management SaaS. Demo Version 1.0.</p>
          <p className="flex items-center space-x-1">
            <span>Dibuat dengan</span>
            <Heart className="w-3.5 h-3.5 text-rose-500 fill-rose-500 inline" />
            <span>untuk Pengusaha Kos Indonesia</span>
          </p>
        </div>
      </div>
    </footer>
  );
}
