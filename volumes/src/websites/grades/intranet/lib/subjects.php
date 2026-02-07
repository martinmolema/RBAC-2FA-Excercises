<?php
// Lijst met mogelijke vakken
$vakken = [
  "Netwerkbeveiliging", "Ethical Hacking", "Cryptografie", "Data Privacy", "Cyber Forensics",
  "Software Security", "Web Security", "Cloud Security", "Incident Response", "Penetration Testing",
  "Malware Analysis", "Security Management", "Risk Assessment", "Digital Forensics", "IoT Security",
  "Blockchain Security", "Mobile Security", "AI Security", "Security Policies", "Compliance",
  "Security Auditing", "Identity Management", "Access Control", "Threat Intelligence", "Vulnerability Management",
  "Programmeren C++", "Software Ontwerp", "Database Management", "Algoritmen en Datastructuren", "Web Development",
  "Mobiele Applicatieontwikkeling", "Software Testing", "Agile Methodologieën", "DevOps", "Project Management"
];
$vakkenMetCodes = [];
foreach ($vakken as $index => $vak) {
  // Genereer vakcode: eerste drie letters (zonder spaties) + oplopend nummer
  $code = strtoupper(substr(str_replace(' ', '', $vak), 0, 3)) . str_pad($index + 1, 2, "0", STR_PAD_LEFT);
  $vakkenMetCodes[] = ['code' => $code, 'name' => $vak];
}
