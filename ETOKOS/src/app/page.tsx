"use client";

import React, { useState } from "react";
import Link from "next/link";
import { Navbar } from "@/components/layout/Navbar";
import { Footer } from "@/components/layout/Footer";
import { useKosStore } from "@/lib/store";
import { formatRupiah, buildWhatsAppLink } from "@/lib/formatters";
import {
  Search,
  MapPin,
  Building2,
  Sparkles,
  Wifi,
  Wind,
  Bath,
  Car,
  ShieldCheck,
  Check,
  ArrowRight,
  Filter,
  MessageCircle,
} from "lucide-react";

export default function HomePage() {
  const { properties, rooms } = useKosStore();
  const [searchTerm, setSearchTerm] = useState("");
  const [selectedType, setSelectedType] = useState<string>("ALL");
  const [priceFilter, setPriceFilter] = useState<string>("ALL");
  const [selectedFacility, setSelectedFacility] = useState<string>("ALL");

  // Calculate available rooms per property
  const getAvailableRoomsCount = (propertyId: string) => {
    return rooms.filter((r) => r.propertyId === propertyId && r.status === "TERSEDIA").length;
  };

  const getStartingPrice = (propertyId: string) => {
    const propRooms = rooms.filter((r) => r.propertyId === propertyId);
    if (!propRooms.length) return 0;
    return Math.min(...propRooms.map((r) => r.priceMonthly));
  };

  // Filter properties
  const filteredProperties = properties.filter((prop) => {
    const matchSearch =
      prop.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
      prop.city.toLowerCase().includes(searchTerm.toLowerCase()) ||
      prop.address.toLowerCase().includes(searchTerm.toLowerCase());

    const matchType = selectedType === "ALL" || prop.type === selectedType;

    const startPrice = getStartingPrice(prop.id);
    let matchPrice = true;
    if (priceFilter === "UNDER_15") matchPrice = startPrice < 1500000;
    else if (priceFilter === "15_TO_25") matchPrice = startPrice >= 1500000 && startPrice <= 2500000;
    else if (priceFilter === "ABOVE_25") matchPrice = startPrice > 2500000;

    const matchFacility =
      selectedFacility === "ALL" ||
      prop.facilities.some((f) => f.toLowerCase().includes(selectedFacility.toLowerCase()));

    return matchSearch && matchType && matchPrice && matchFacility;
  });

  return (
    <>
      <Navbar />

      {/* Hero Section */}
      <section className="relative bg-gradient-to-br from-amber-500 via-orange-500 to-amber-600 text-white py-16 sm:py-20 px-4 sm:px-6 lg:px-8 overflow-hidden shadow-inner">
        <div className="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]" />
        
        <div className="relative max-w-4xl mx-auto text-center space-y-6">
          <div className="inline-flex items-center space-x-2 px-3.5 py-1 rounded-full bg-white/20 backdrop-blur-md text-yellow-100 text-xs font-semibold border border-white/20 shadow-xs">
            <Sparkles className="w-3.5 h-3.5 text-yellow-300" />
            <span>Sistem Manajemen & Listing Kos Indonesia</span>
          </div>

          <h1 className="text-3xl sm:text-5xl font-black tracking-tight leading-tight">
            Cari Kos Nyaman, Modern & Transparan di Indonesia
          </h1>

          <p className="text-sm sm:text-base text-amber-100 max-w-2xl mx-auto font-normal">
            Daftar kamar terisi real-time, invoice digital dengan rincian jelas, WhatsApp reminder, dan pelayanan servis terpadu.
          </p>

          {/* Search Box */}
          <div className="bg-white rounded-2xl p-2 sm:p-3 shadow-2xl max-w-2xl mx-auto flex flex-col sm:flex-row gap-2 border border-orange-200">
            <div className="flex-1 flex items-center px-3 py-2 text-slate-800">
              <Search className="w-5 h-5 text-orange-500 mr-2 flex-shrink-0" />
              <input
                type="text"
                placeholder="Cari lokasi kos (BSD, Sleman, Gejayan, dll)..."
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                className="w-full text-xs sm:text-sm outline-none text-slate-800 placeholder-slate-400 bg-transparent"
              />
            </div>
            <button
              onClick={() => {}}
              className="px-6 py-3 bg-gradient-to-r from-orange-600 to-amber-600 hover:from-orange-700 hover:to-amber-700 text-white text-xs sm:text-sm font-bold rounded-xl transition shadow-md flex items-center justify-center space-x-2"
            >
              <Search className="w-4 h-4" />
              <span>Temukan Kos</span>
            </button>
          </div>
        </div>
      </section>

      {/* Filter Bar */}
      <section className="bg-white border-b border-slate-200 py-4 px-4 sm:px-6 lg:px-8 sticky top-[105px] z-20 shadow-xs">
        <div className="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-3 text-xs">
          <div className="flex items-center space-x-2 text-slate-600 font-semibold">
            <Filter className="w-4 h-4 text-orange-500" />
            <span>Filter Cepat:</span>
          </div>

          {/* Tipe Kos Filter */}
          <div className="flex items-center space-x-1 overflow-x-auto pb-1 sm:pb-0">
            {["ALL", "Campur", "Putri", "Putra"].map((type) => (
              <button
                key={type}
                onClick={() => setSelectedType(type)}
                className={`px-3 py-1.5 rounded-lg font-medium transition whitespace-nowrap ${
                  selectedType === type
                    ? "bg-orange-600 text-white font-bold shadow-xs"
                    : "bg-slate-100 text-slate-600 hover:bg-slate-200"
                }`}
              >
                {type === "ALL" ? "Semua Tipe" : `Kos ${type}`}
              </button>
            ))}
          </div>

          {/* Rentang Harga */}
          <div className="flex items-center space-x-1 overflow-x-auto pb-1 sm:pb-0">
            {[
              { id: "ALL", label: "Semua Harga" },
              { id: "UNDER_15", label: "< 1.5 Jt" },
              { id: "15_TO_25", label: "1.5 - 2.5 Jt" },
              { id: "ABOVE_25", label: "> 2.5 Jt" },
            ].map((p) => (
              <button
                key={p.id}
                onClick={() => setPriceFilter(p.id)}
                className={`px-3 py-1.5 rounded-lg font-medium transition whitespace-nowrap ${
                  priceFilter === p.id
                    ? "bg-orange-600 text-white font-bold shadow-xs"
                    : "bg-slate-100 text-slate-600 hover:bg-slate-200"
                }`}
              >
                {p.label}
              </button>
            ))}
          </div>

          {/* Fasilitas Populer */}
          <div className="flex items-center space-x-1">
            {[
              { id: "ALL", label: "Semua Fasilitas" },
              { id: "AC", label: "❄️ Ber-AC" },
              { id: "WiFi", label: "📶 WiFi" },
              { id: "Kamar Mandi Dalam", label: "🚿 KM Dalam" },
            ].map((f) => (
              <button
                key={f.id}
                onClick={() => setSelectedFacility(f.id)}
                className={`px-3 py-1.5 rounded-lg font-medium transition whitespace-nowrap ${
                  selectedFacility === f.id
                    ? "bg-orange-600 text-white font-bold shadow-xs"
                    : "bg-slate-100 text-slate-600 hover:bg-slate-200"
                }`}
              >
                {f.label}
              </button>
            ))}
          </div>
        </div>
      </section>

      {/* Main Listing Section */}
      <main className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex-1">
        <div className="flex items-center justify-between mb-6">
          <div>
            <h2 className="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
              Properti Kos Tersedia
            </h2>
            <p className="text-xs text-slate-500">
              Menampilkan {filteredProperties.length} properti terdaftar dengan data kamar akurat
            </p>
          </div>
        </div>

        {filteredProperties.length === 0 ? (
          <div className="text-center py-16 bg-white rounded-2xl border border-slate-200 p-8 space-y-4">
            <Building2 className="w-12 h-12 text-slate-300 mx-auto" />
            <h3 className="text-base font-bold text-slate-800">Tidak ada kos yang cocok</h3>
            <p className="text-xs text-slate-500 max-w-sm mx-auto">
              Coba ganti filter tipe kos, harga, atau fasilitas untuk menemukan properti lainnya.
            </p>
            <button
              onClick={() => {
                setSearchTerm("");
                setSelectedType("ALL");
                setPriceFilter("ALL");
                setSelectedFacility("ALL");
              }}
              className="px-4 py-2 bg-orange-600 text-white text-xs font-semibold rounded-lg hover:bg-orange-700 transition"
            >
              Reset Semua Filter
            </button>
          </div>
        ) : (
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {filteredProperties.map((prop) => {
              const availableRooms = getAvailableRoomsCount(prop.id);
              const startPrice = getStartingPrice(prop.id);
              const waLink = buildWhatsAppLink(
                prop.managerPhone,
                `Halo ${prop.managerName}, saya ingin menanyakan ketersediaan kamar di ${prop.name}. Apakah masih ada kamar kosong? Terima kasih!`
              );

              return (
                <div
                  key={prop.id}
                  className="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-lg transition flex flex-col justify-between group"
                >
                  <div>
                    {/* Image & badges */}
                    <div className="relative h-64 overflow-hidden">
                      <img
                        src={prop.coverImage}
                        alt={prop.name}
                        className="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                      />
                      <div className="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent" />

                      {/* Badges */}
                      <div className="absolute top-4 left-4 flex flex-wrap gap-2">
                        <span className="px-3 py-1 rounded-full text-xs font-bold bg-white text-slate-900 shadow">
                          Kos {prop.type}
                        </span>
                        <span
                          className={`px-3 py-1 rounded-full text-xs font-bold shadow ${
                            availableRooms > 0
                              ? "bg-emerald-500 text-white"
                              : "bg-rose-500 text-white"
                          }`}
                        >
                          {availableRooms > 0
                            ? `Sisa ${availableRooms} Kamar Kosong`
                            : "Kamar Penuh"}
                        </span>
                      </div>

                      <div className="absolute bottom-4 left-4 right-4 text-white">
                        <h3 className="text-xl font-extrabold tracking-tight">{prop.name}</h3>
                        <p className="text-xs text-slate-200 flex items-center space-x-1 mt-0.5">
                          <MapPin className="w-3.5 h-3.5 text-orange-400 flex-shrink-0" />
                          <span className="truncate">{prop.address}, {prop.city}</span>
                        </p>
                      </div>
                    </div>

                    {/* Body */}
                    <div className="p-6 space-y-4">
                      <p className="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                        {prop.description}
                      </p>

                      {/* Highlighted Facilities */}
                      <div className="space-y-1.5">
                        <p className="text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                          Fasilitas Unggulan:
                        </p>
                        <div className="flex flex-wrap gap-1.5">
                          {prop.facilities.slice(0, 5).map((fac, idx) => (
                            <span
                              key={idx}
                              className="px-2.5 py-1 rounded-md bg-orange-50 text-orange-800 text-[11px] font-medium border border-orange-100 flex items-center space-x-1"
                            >
                              <Check className="w-3 h-3 text-orange-600" />
                              <span>{fac}</span>
                            </span>
                          ))}
                          {prop.facilities.length > 5 && (
                            <span className="px-2 py-1 text-[11px] text-slate-500 font-medium">
                              +{prop.facilities.length - 5} lainnya
                            </span>
                          )}
                        </div>
                      </div>
                    </div>
                  </div>

                  {/* Footer & CTA */}
                  <div className="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                    <div>
                      <p className="text-[11px] text-slate-500 font-medium">Mulai dari</p>
                      <p className="text-lg font-black text-orange-600">
                        {formatRupiah(startPrice)}
                        <span className="text-xs font-normal text-slate-500"> / bulan</span>
                      </p>
                    </div>

                    <div className="flex items-center space-x-2">
                      <a
                        href={waLink}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="p-2.5 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 transition"
                        title="Chat Pengelola via WhatsApp"
                      >
                        <MessageCircle className="w-4 h-4" />
                      </a>
                      <Link
                        href={`/kos/${prop.id}`}
                        className="px-4 py-2.5 rounded-xl bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold transition shadow-sm flex items-center space-x-1.5"
                      >
                        <span>Lihat Kamar & Booking</span>
                        <ArrowRight className="w-3.5 h-3.5" />
                      </Link>
                    </div>
                  </div>
                </div>
              );
            })}
          </div>
        )}
      </main>

      <Footer />
    </>
  );
}
