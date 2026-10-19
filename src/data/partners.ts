// Partner logos are stored in /public/images/partners/ (never hotlinked).

export type Partner = { name: string; logo: string };
export type PartnerGroup = { title: string; partners: Partner[] };

const logo = (file: string) => `/images/partners/${file}`;

export const partnerGroups: PartnerGroup[] = [
  {
    title: 'Government & public sector',
    partners: [
      { name: 'Government of Telangana', logo: logo('government-of-telangana.png') },
      { name: 'Government of Andhra Pradesh', logo: logo('government-of-andhra-pradesh.png') },
      { name: 'Telangana State Police', logo: logo('telangana-state-police.png') },
      { name: 'EMRI', logo: logo('emri.png') },
      { name: 'eGovernments Foundation', logo: logo('egovernments-foundation.png') },
    ],
  },
  {
    title: 'Industry',
    partners: [
      { name: 'ABB', logo: logo('abb.svg') },
      { name: 'Airtel', logo: logo('airtel.svg') },
      { name: 'CallHealth', logo: logo('callhealth.png') },
      { name: 'Schneider Electric', logo: logo('schneider-electric.svg') },
      { name: 'Tech Mahindra', logo: logo('tech-mahindra.svg') },
      { name: 'AceLine Tech Solutions', logo: logo('aceline-tech-solutions.png') },
    ],
  },
  {
    title: 'Academia & professional bodies',
    partners: [
      { name: 'IIIT Hyderabad', logo: logo('iiit-hyderabad.png') },
      { name: 'Computer Society of India', logo: logo('computer-society-of-india.png') },
    ],
  },
];

export const allPartners = partnerGroups.flatMap((g) => g.partners);
