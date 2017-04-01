CREATE TABLE IF NOT EXISTS `industry_coc` (
  `industry` varchar(100) NOT NULL,
  `beta` decimal(30,15) NOT NULL,
  `cost_equity` decimal(30,15) NOT NULL,
  `cost_debt` decimal(30,15) NOT NULL,
  `cost_capital` decimal(30,15) NOT NULL,
  PRIMARY KEY (`industry`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `industry_coc` (`industry`, `beta`, `cost_equity`, `cost_debt`, `cost_capital`) VALUES
('Advertising', 1.362998800000000, 0.102054634000000, 0.040500000000000, 0.072009397000000),
('Aerospace & Defense', 1.074487500000000, 0.085638337000000, 0.037000000000000, 0.073556109000000),
('Agricultural & Farm Machinery', 1.062374600000000, 0.084949113000000, 0.035000000000000, 0.073548505000000),
('Agricultural Products', 0.919292800000000, 0.076807762000000, 0.037000000000000, 0.056322251000000),
('Air Freight & Logistics', 1.122088800000000, 0.088346852000000, 0.035000000000000, 0.060588681000000),
('Airlines', 1.122088800000000, 0.088346852000000, 0.035000000000000, 0.060588681000000),
('Airport Services', 1.122088800000000, 0.088346852000000, 0.035000000000000, 0.060588681000000),
('Alternative Carriers', 1.042429000000000, 0.083814209000000, 0.037000000000000, 0.059341858000000),
('Aluminum', 1.303872200000000, 0.098690328000000, 0.052000000000000, 0.075607415000000),
('Apparel Retail', 0.880540500000000, 0.074602754000000, 0.037000000000000, 0.061244251000000),
('Apparel, Accessories & Luxury Goods', 0.880540500000000, 0.074602754000000, 0.037000000000000, 0.061244251000000),
('Application Software', 1.125760200000000, 0.088555756000000, 0.037000000000000, 0.076813191000000),
('Asset Management & Custody Banks', 0.895979600000000, 0.075481240000000, 0.035000000000000, 0.054715966000000),
('Auto Components', 1.122629700000000, 0.088377631000000, 0.037000000000000, 0.071140522000000),
('Auto Parts & Equipment', 1.122629700000000, 0.088377631000000, 0.037000000000000, 0.071140522000000),
('Automobile Manufacturers', 0.845010800000000, 0.072581114000000, 0.035000000000000, 0.041597754000000),
('Automotive Retail', 0.913034800000000, 0.076451682000000, 0.035000000000000, 0.055681113000000),
('Beverages', 0.911339100000000, 0.076355196000000, 0.037000000000000, 0.065693602000000),
('Biotechnology', 1.402457500000000, 0.104299832000000, 0.052000000000000, 0.092399552000000),
('Brewers', 0.792801700000000, 0.069610415000000, 0.037000000000000, 0.058946072000000),
('Broadcasting', 1.220010300000000, 0.093918589000000, 0.037000000000000, 0.058805144000000),
('Building Products', 1.006784300000000, 0.081786026000000, 0.035000000000000, 0.068871716000000),
('Cable & Satellite', 1.115739000000000, 0.087985551000000, 0.035000000000000, 0.065885695000000),
('Capital Markets', 0.654507900000000, 0.061741497000000, 0.035000000000000, 0.024327449000000),
('Casinos & Gaming', 0.960901300000000, 0.079175282000000, 0.035000000000000, 0.058465720000000),
('Chemicals', 1.002633500000000, 0.081549844000000, 0.037000000000000, 0.059617393000000),
('Coal & Consumable Fuels', 1.363240800000000, 0.102068401000000, 0.040500000000000, 0.056899810000000),
('Commercial Printing', 1.320277200000000, 0.099623771000000, 0.037000000000000, 0.071531523000000),
('Commodity Chemicals', 1.002633500000000, 0.081549844000000, 0.037000000000000, 0.059617393000000),
('Communications Equipment', 0.988585300000000, 0.080750505000000, 0.037000000000000, 0.069209475000000),
('Computer & Electronics Retail', 1.083405000000000, 0.086145744000000, 0.040500000000000, 0.076102574000000),
('Construction & Engineering', 1.181064000000000, 0.091702543000000, 0.037000000000000, 0.074672866000000),
('Construction & Farm Machinery & Heavy Trucks', 1.062374600000000, 0.084949113000000, 0.035000000000000, 0.073548505000000),
('Construction Materials', 1.314828600000000, 0.099313747000000, 0.035000000000000, 0.075928680000000),
('Consumer Electronics', 1.083405000000000, 0.086145744000000, 0.040500000000000, 0.076102574000000),
('Consumer Finance', 1.070528000000000, 0.085413043000000, 0.037000000000000, 0.068990563000000),
('Containers & Packaging', 0.838636600000000, 0.072218425000000, 0.035000000000000, 0.053825413000000),
('Copper', 1.303872200000000, 0.098690328000000, 0.052000000000000, 0.075607415000000),
('Data Processing & Outsourced Services', 1.070528000000000, 0.085413043000000, 0.037000000000000, 0.068990563000000),
('Department Stores', 1.048330500000000, 0.084150008000000, 0.037000000000000, 0.066585380000000),
('Distillers & Vintners', 0.792801700000000, 0.069610415000000, 0.037000000000000, 0.058946072000000),
('Distributors', 1.104604500000000, 0.087351998000000, 0.037000000000000, 0.063710157000000),
('Diversified Banks', 0.860229000000000, 0.073447029000000, 0.035000000000000, 0.039208900000000),
('Diversified Capital Markets', 0.654507900000000, 0.061741497000000, 0.035000000000000, 0.024327449000000),
('Diversified Chemicals', 1.518895900000000, 0.110925174000000, 0.037000000000000, 0.087668135000000),
('Diversified Consumer Services', 1.070528000000000, 0.085413043000000, 0.037000000000000, 0.068990563000000),
('Diversified Financial Services', 0.654507900000000, 0.061741497000000, 0.035000000000000, 0.024327449000000),
('Diversified Metals & Mining', 1.303872200000000, 0.098690328000000, 0.052000000000000, 0.075607415000000),
('Diversified Real Estate Activities', 1.270788400000000, 0.096807863000000, 0.030500000000000, 0.081444559000000),
('Diversified REITs', 0.724807300000000, 0.065741537000000, 0.030500000000000, 0.044736180000000),
('Diversified Support Services', 1.070528000000000, 0.085413043000000, 0.037000000000000, 0.068990563000000),
('Drug Retail', 1.015395200000000, 0.082275989000000, 0.040500000000000, 0.074898793000000),
('Education Services', 1.225147200000000, 0.094210875000000, 0.037000000000000, 0.076188622000000),
('Electric Utilities', 0.376499500000000, 0.045922820000000, 0.030500000000000, 0.034705859000000),
('Electrical Components & Equipment', 1.143250100000000, 0.089550930000000, 0.037000000000000, 0.078725297000000),
('Electronic Components', 0.864158300000000, 0.073670608000000, 0.037000000000000, 0.065865497000000),
('Electronic Equipment & Instruments', 0.864158300000000, 0.073670608000000, 0.037000000000000, 0.065865497000000),
('Electronic Equipment, Instruments & Components', 0.864158300000000, 0.073670608000000, 0.037000000000000, 0.065865497000000),
('Electronic Instr. & Controls', 0.864158300000000, 0.073670608000000, 0.037000000000000, 0.065865497000000),
('Electronic Manufacturing Services', 1.083405000000000, 0.086145744000000, 0.040500000000000, 0.076102574000000),
('Environmental & Facilities Services', 0.852223100000000, 0.072991492000000, 0.037000000000000, 0.058744857000000),
('Equity Real Estate Investment Trusts (REITs)', 0.724807300000000, 0.065741537000000, 0.030500000000000, 0.044736180000000),
('Fertilizers & Agricultural Chemicals', 0.919292800000000, 0.076807762000000, 0.037000000000000, 0.056322251000000),
('Financial Exchanges & Data', 0.654507900000000, 0.061741497000000, 0.035000000000000, 0.024327449000000),
('Food & Staples Retailing', 0.690068800000000, 0.063764912000000, 0.035000000000000, 0.046630181000000),
('Food Distributors', 1.203724700000000, 0.092991933000000, 0.037000000000000, 0.074124492000000),
('Food Retail', 0.690068800000000, 0.063764912000000, 0.035000000000000, 0.046630181000000),
('Footwear', 0.851920600000000, 0.072974280000000, 0.035000000000000, 0.068138777000000),
('Forest Products', 1.118469800000000, 0.088140934000000, 0.035000000000000, 0.065958633000000),
('Gas Utilities', 0.376499500000000, 0.045922820000000, 0.030500000000000, 0.034705859000000),
('General Merchandise Stores', 1.048330500000000, 0.084150008000000, 0.037000000000000, 0.066585380000000),
('Gold', 1.251165800000000, 0.095691336000000, 0.052000000000000, 0.083164812000000),
('Ground Freight & Logistics', 1.013576500000000, 0.082172504000000, 0.035000000000000, 0.067351118000000),
('Health Care Distributors', 0.938830200000000, 0.077919440000000, 0.037000000000000, 0.064354732000000),
('Health Care Equipment', 1.036305200000000, 0.083465764000000, 0.037000000000000, 0.074138799000000),
('Health Care Facilities', 1.097826000000000, 0.086966300000000, 0.037000000000000, 0.046119707000000),
('Health Care Providers & Services', 0.938830200000000, 0.077919440000000, 0.037000000000000, 0.064354732000000),
('Health Care REITs', 0.724807300000000, 0.065741537000000, 0.030500000000000, 0.044736180000000),
('Health Care Services', 0.938830200000000, 0.077919440000000, 0.037000000000000, 0.064354732000000),
('Health Care Supplies', 1.036305200000000, 0.083465764000000, 0.037000000000000, 0.074138799000000),
('Health Care Technology', 0.950516300000000, 0.078584376000000, 0.037000000000000, 0.068915081000000),
('Healthcare Facilities & Services', 1.097826000000000, 0.086966300000000, 0.037000000000000, 0.046119707000000),
('Heavy Electrical Equipment', 1.143250100000000, 0.089550930000000, 0.037000000000000, 0.078725297000000),
('Holding Companies', 0.895979600000000, 0.075481240000000, 0.035000000000000, 0.054715966000000),
('Home Entertainment Software', 0.980741700000000, 0.080304204000000, 0.037000000000000, 0.072838924000000),
('Home Furnishings', 0.835642200000000, 0.072048041000000, 0.037000000000000, 0.060839341000000),
('Home Improvement Retail', 1.077566600000000, 0.085813539000000, 0.035000000000000, 0.059975660000000),
('Homebuilding', 1.077566600000000, 0.085813539000000, 0.035000000000000, 0.059975660000000),
('Homefurnishing Retail', 0.835642200000000, 0.072048041000000, 0.037000000000000, 0.060839341000000),
('Hotel & Resort REITs', 0.724807300000000, 0.065741537000000, 0.030500000000000, 0.044736180000000),
('Hotels, Resorts & Cruise Lines', 0.960901300000000, 0.079175282000000, 0.035000000000000, 0.058465720000000),
('Hotels, Restaurants & Leisure', 0.960901300000000, 0.079175282000000, 0.035000000000000, 0.058465720000000),
('Household Appliances', 0.796524600000000, 0.069822249000000, 0.037000000000000, 0.061491350000000),
('Household Products', 0.796524600000000, 0.069822249000000, 0.037000000000000, 0.061491350000000),
('Housewares & Specialties', 0.796524600000000, 0.069822249000000, 0.037000000000000, 0.061491350000000),
('Human Resource & Employment Services', 1.070528000000000, 0.085413043000000, 0.037000000000000, 0.068990563000000),
('Hypermarkets & Super Centers', 1.048330500000000, 0.084150008000000, 0.037000000000000, 0.066585380000000),
('Independent Power Producers & Energy Traders', 0.544082100000000, 0.055458270000000, 0.030500000000000, 0.038138487000000),
('Industrial Conglomerates', 0.764571400000000, 0.068004111000000, 0.030500000000000, 0.054851963000000),
('Industrial Gases', 1.080586100000000, 0.085985350000000, 0.037000000000000, 0.076761069000000),
('Industrial Machinery', 1.062374600000000, 0.084949113000000, 0.035000000000000, 0.073548505000000),
('Industrial REITs', 0.724807300000000, 0.065741537000000, 0.030500000000000, 0.044736180000000),
('Insurance Brokers', 0.904797300000000, 0.075982969000000, 0.035000000000000, 0.060569943000000),
('Integrated Oil & Gas', 1.080586100000000, 0.085985350000000, 0.037000000000000, 0.076761069000000),
('Integrated Telecommunication Services', 1.042429000000000, 0.083814209000000, 0.037000000000000, 0.059341858000000),
('Internet & Direct Marketing Retail', 1.227182500000000, 0.094326685000000, 0.037000000000000, 0.087993460000000),
('Internet Software & Services', 1.133334100000000, 0.088986709000000, 0.037000000000000, 0.086253303000000),
('Investment Banking & Brokerage', 1.076942400000000, 0.085778021000000, 0.037000000000000, 0.041337869000000),
('IT Consulting & Other Services', 0.981280200000000, 0.080334845000000, 0.035000000000000, 0.070257885000000),
('IT Services', 0.981280200000000, 0.080334845000000, 0.035000000000000, 0.070257885000000),
('Leisure Facilities', 0.920928300000000, 0.076900823000000, 0.037000000000000, 0.063918950000000),
('Leisure Products', 0.920928300000000, 0.076900823000000, 0.037000000000000, 0.063918950000000),
('Life & Health Insurance', 1.034404000000000, 0.083357587000000, 0.035000000000000, 0.057379482000000),
('Life Sciences Tools & Services', 0.950516300000000, 0.078584376000000, 0.037000000000000, 0.068915081000000),
('Managed Health Care', 0.938830200000000, 0.077919440000000, 0.037000000000000, 0.064354732000000),
('Marine', 1.202475700000000, 0.092920867000000, 0.040500000000000, 0.070537790000000),
('Materials', 1.314828600000000, 0.099313747000000, 0.035000000000000, 0.075928680000000),
('Media', 1.202356800000000, 0.092914099000000, 0.037000000000000, 0.076251671000000),
('Metal & Glass Containers', 1.303872200000000, 0.098690328000000, 0.052000000000000, 0.075607415000000),
('Metals & Mining', 1.303872200000000, 0.098690328000000, 0.052000000000000, 0.075607415000000),
('Mortgage REITs', 0.724807300000000, 0.065741537000000, 0.030500000000000, 0.044736180000000),
('Motorcycle Manufacturers', 0.845010800000000, 0.072581114000000, 0.035000000000000, 0.041597754000000),
('Movies & Entertainment', 1.202356800000000, 0.092914099000000, 0.037000000000000, 0.076251671000000),
('Multi-line Insurance', 0.904797300000000, 0.075982969000000, 0.035000000000000, 0.060569943000000),
('Multi-Sector Holdings', 0.764571400000000, 0.068004111000000, 0.030500000000000, 0.054851963000000),
('Multi-Utilities', 0.376499500000000, 0.045922820000000, 0.030500000000000, 0.034705859000000),
('Office REITs', 0.724807300000000, 0.065741537000000, 0.030500000000000, 0.044736180000000),
('Office Services & Supplies', 1.490142300000000, 0.109289097000000, 0.035000000000000, 0.078211763000000),
('Oil & Gas Drilling', 1.377352100000000, 0.102871336000000, 0.052000000000000, 0.079961290000000),
('Oil & Gas Equipment & Services', 1.365760600000000, 0.102211778000000, 0.037000000000000, 0.082513198000000),
('Oil & Gas Exploration & Production', 1.377352100000000, 0.102871336000000, 0.052000000000000, 0.079961290000000),
('Oil & Gas Refining & Marketing', 1.195250300000000, 0.092509745000000, 0.037000000000000, 0.061912281000000),
('Oil & Gas Storage & Transportation', 1.195250300000000, 0.092509745000000, 0.037000000000000, 0.061912281000000),
('Oil, Gas & Consumable Fuels', 1.195250300000000, 0.092509745000000, 0.037000000000000, 0.061912281000000),
('Other Diversified Financial Services', 0.764571400000000, 0.068004111000000, 0.030500000000000, 0.054851963000000),
('Packaged Foods & Meats', 0.753736500000000, 0.067387605000000, 0.035000000000000, 0.057570503000000),
('Paper Packaging', 0.838636600000000, 0.072218425000000, 0.035000000000000, 0.053825413000000),
('Paper Products', 1.118469800000000, 0.088140934000000, 0.035000000000000, 0.065958633000000),
('Personal Products', 0.796524600000000, 0.069822249000000, 0.037000000000000, 0.061491350000000),
('Pharmaceuticals', 1.015395200000000, 0.082275989000000, 0.040500000000000, 0.074898793000000),
('Precious Metals & Minerals', 1.251165800000000, 0.095691336000000, 0.052000000000000, 0.083164812000000),
('Property & Casualty Insurance', 0.830120700000000, 0.071733868000000, 0.035000000000000, 0.059696790000000),
('Publishing', 1.320277200000000, 0.099623771000000, 0.037000000000000, 0.071531523000000),
('Railroads', 0.786016000000000, 0.069224312000000, 0.035000000000000, 0.058692939000000),
('Real Estate Development', 0.684268200000000, 0.063434858000000, 0.035000000000000, 0.047438172000000),
('Real Estate Operating Companies', 0.991680800000000, 0.080926637000000, 0.037000000000000, 0.055441256000000),
('Real Estate Services', 0.991680800000000, 0.080926637000000, 0.037000000000000, 0.055441256000000),
('Regional Banks', 0.471938200000000, 0.051353284000000, 0.030500000000000, 0.038892503000000),
('Reinsurance', 0.747361400000000, 0.067024862000000, 0.030500000000000, 0.055108202000000),
('Renewable Electricity', 1.140302500000000, 0.089383213000000, 0.037000000000000, 0.046685243000000),
('Research & Consulting Services', 1.070528000000000, 0.085413043000000, 0.037000000000000, 0.068990563000000),
('Residential REITs', 0.724807300000000, 0.065741537000000, 0.030500000000000, 0.044736180000000),
('Restaurants', 0.768290300000000, 0.068215718000000, 0.035000000000000, 0.055973265000000),
('Retail REITs', 0.724807300000000, 0.065741537000000, 0.030500000000000, 0.044736180000000),
('Security & Alarm Services', 1.125760200000000, 0.088555756000000, 0.037000000000000, 0.076813191000000),
('Semiconductor Equipment', 1.097256400000000, 0.086933890000000, 0.035000000000000, 0.077669524000000),
('Semiconductors', 1.195275300000000, 0.092511163000000, 0.037000000000000, 0.083826873000000),
('Semiconductors & Semiconductor Equipment', 1.097256400000000, 0.086933890000000, 0.035000000000000, 0.077669524000000),
('Silver', 1.251165800000000, 0.095691336000000, 0.052000000000000, 0.083164812000000),
('Soft Drinks', 0.911339100000000, 0.076355196000000, 0.037000000000000, 0.065693602000000),
('Software', 1.125760200000000, 0.088555756000000, 0.037000000000000, 0.076813191000000),
('Specialized Consumer Services', 1.070528000000000, 0.085413043000000, 0.037000000000000, 0.068990563000000),
('Specialized Finance', 0.654507900000000, 0.061741497000000, 0.035000000000000, 0.024327449000000),
('Specialized REITs', 0.724807300000000, 0.065741537000000, 0.030500000000000, 0.044736180000000),
('Specialty Chemicals', 1.198054200000000, 0.092669283000000, 0.037000000000000, 0.076416324000000),
('Specialty Stores', 1.022467000000000, 0.082678373000000, 0.037000000000000, 0.061955412000000),
('Steel', 1.599676100000000, 0.115521570000000, 0.037000000000000, 0.085285817000000),
('Systems Software', 1.125760200000000, 0.088555756000000, 0.037000000000000, 0.076813191000000),
('Technology Distributors', 0.985286900000000, 0.080562827000000, 0.037000000000000, 0.067678140000000),
('Technology Hardware, Storage & Peripherals', 1.055442800000000, 0.084554696000000, 0.037000000000000, 0.074336629000000),
('Textiles', 1.048330500000000, 0.084150008000000, 0.037000000000000, 0.066585380000000),
('Thrifts & Mortgage Finance', 0.991680800000000, 0.080926637000000, 0.037000000000000, 0.055441256000000),
('Tires & Rubber', 1.345535000000000, 0.101060944000000, 0.037000000000000, 0.067957089000000),
('Tobacco', 1.279160400000000, 0.097284224000000, 0.037000000000000, 0.086273664000000),
('Trading Companies & Distributors', 1.104604500000000, 0.087351998000000, 0.037000000000000, 0.063710157000000),
('Trucking', 1.205034600000000, 0.093066470000000, 0.037000000000000, 0.059342489000000),
('Water Utilities', 0.648851900000000, 0.061419671000000, 0.035000000000000, 0.049173877000000),
('Wireless Telecommunication Services', 1.115789700000000, 0.087988435000000, 0.035000000000000, 0.052218464000000);
