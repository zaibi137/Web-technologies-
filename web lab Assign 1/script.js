// Global variables
let currentTemplate = 'modern';
let skills = [];
let zoomLevel = 1;

// Template selection
function selectTemplate(template) {
    currentTemplate = template;
    
    // Update active state
    document.querySelectorAll('.template-option').forEach(opt => {
        opt.classList.remove('active');
    });
    document.querySelector(`[data-template="${template}"]`).classList.add('active');
    
    // Update preview
    generateCV();
}

// Add education field
function addEducationField() {
    const container = document.getElementById('educationFields');
    const newField = document.createElement('div');
    newField.className = 'education-entry border rounded p-3 mb-2';
    newField.innerHTML = `
        <div class="row g-2">
            <div class="col-12">
                <label class="form-label small">Degree</label>
                <input type="text" class="form-control form-control-sm edu-degree" placeholder="Degree/Certificate">
            </div>
            <div class="col-8">
                <label class="form-label small">Institution</label>
                <input type="text" class="form-control form-control-sm edu-institution" placeholder="Institution Name">
            </div>
            <div class="col-4">
                <label class="form-label small">Year</label>
                <input type="text" class="form-control form-control-sm edu-year" placeholder="Year">
            </div>
            <div class="col-12">
                <label class="form-label small">Description</label>
                <textarea class="form-control form-control-sm edu-description" rows="2" placeholder="Relevant achievements..."></textarea>
            </div>
            <div class="col-12">
                <button class="btn btn-outline-danger btn-sm" onclick="this.parentElement.parentElement.parentElement.remove()">
                    <i class="fas fa-trash me-1"></i>Remove
                </button>
            </div>
        </div>
    `;
    container.appendChild(newField);
}

// Add experience field
function addExperienceField() {
    const container = document.getElementById('experienceFields');
    const newField = document.createElement('div');
    newField.className = 'experience-entry border rounded p-3 mb-2';
    newField.innerHTML = `
        <div class="row g-2">
            <div class="col-12">
                <label class="form-label small">Job Title</label>
                <input type="text" class="form-control form-control-sm exp-title" placeholder="Job Title">
            </div>
            <div class="col-8">
                <label class="form-label small">Company</label>
                <input type="text" class="form-control form-control-sm exp-company" placeholder="Company Name">
            </div>
            <div class="col-4">
                <label class="form-label small">Duration</label>
                <input type="text" class="form-control form-control-sm exp-duration" placeholder="e.g., 2020-Present">
            </div>
            <div class="col-12">
                <label class="form-label small">Description</label>
                <textarea class="form-control form-control-sm exp-description" rows="3" placeholder="Describe your responsibilities and achievements..."></textarea>
            </div>
            <div class="col-12">
                <button class="btn btn-outline-danger btn-sm" onclick="this.parentElement.parentElement.parentElement.remove()">
                    <i class="fas fa-trash me-1"></i>Remove
                </button>
            </div>
        </div>
    `;
    container.appendChild(newField);
}

// Add skill
function addSkill() {
    const input = document.getElementById('skillInput');
    const skill = input.value.trim();
    
    if (skill && !skills.includes(skill)) {
        skills.push(skill);
        renderSkills();
        input.value = '';
    }
}

// Add skill on Enter key
document.getElementById('skillInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        addSkill();
    }
});

// Remove skill
function removeSkill(skill) {
    skills = skills.filter(s => s !== skill);
    renderSkills();
}

// Render skills
function renderSkills() {
    const container = document.getElementById('skillsList');
    container.innerHTML = skills.map(skill => `
        <span class="skill-tag" onclick="removeSkill('${skill}')">
            ${skill} <i class="fas fa-times ms-1"></i>
        </span>
    `).join('');
}

// Get form data
function getFormData() {
    // Personal info
    const personal = {
        firstName: document.getElementById('firstName').value,
        lastName: document.getElementById('lastName').value,
        jobTitle: document.getElementById('jobTitle').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value,
        address: document.getElementById('address').value,
        linkedin: document.getElementById('linkedin').value,
        summary: document.getElementById('summary').value
    };

    // Education
    const education = [];
    document.querySelectorAll('.education-entry').forEach(entry => {
        education.push({
            degree: entry.querySelector('.edu-degree').value,
            institution: entry.querySelector('.edu-institution').value,
            year: entry.querySelector('.edu-year').value,
            description: entry.querySelector('.edu-description').value
        });
    });

    // Experience
    const experience = [];
    document.querySelectorAll('.experience-entry').forEach(entry => {
        experience.push({
            title: entry.querySelector('.exp-title').value,
            company: entry.querySelector('.exp-company').value,
            duration: entry.querySelector('.exp-duration').value,
            description: entry.querySelector('.exp-description').value
        });
    });

    return { personal, education, experience, skills };
}

// Generate CV Preview
function generateCV() {
    const data = getFormData();
    const preview = document.getElementById('cvPreview');
    
    // Add animation class
    preview.classList.remove('animate');
    void preview.offsetWidth; // Trigger reflow
    preview.classList.add('animate');

    // Check if any required data is filled
    if (!data.personal.firstName && !data.personal.lastName && !data.personal.email) {
        preview.innerHTML = `
            <div class="cv-empty">
                <i class="fas fa-file-alt"></i>
                <h4>Your CV Preview</h4>
                <p>Start filling in your information on the left to see your professional CV here.</p>
            </div>
        `;
        return;
    }

    // Generate based on template
    switch(currentTemplate) {
        case 'modern':
            preview.innerHTML = generateModernTemplate(data);
            break;
        case 'classic':
            preview.innerHTML = generateClassicTemplate(data);
            break;
        case 'creative':
            preview.innerHTML = generateCreativeTemplate(data);
            break;
    }
}

// Modern Template
function generateModernTemplate(data) {
    const { personal, education, experience, skills } = data;
    
    return `
        <div class="cv-sidebar">
            ${(personal.firstName || personal.lastName) ? `
                <div class="cv-name">${personal.firstName} ${personal.lastName}</div>
            ` : ''}
            ${personal.jobTitle ? `<div class="cv-job-title">${personal.jobTitle}</div>` : ''}
            
            <div class="cv-section">
                <div class="cv-section-title">Contact</div>
                ${personal.email ? `<div class="cv-contact-item"><i class="fas fa-envelope"></i>${personal.email}</div>` : ''}
                ${personal.phone ? `<div class="cv-contact-item"><i class="fas fa-phone"></i>${personal.phone}</div>` : ''}
                ${personal.address ? `<div class="cv-contact-item"><i class="fas fa-map-marker-alt"></i>${personal.address}</div>` : ''}
                ${personal.linkedin ? `<div class="cv-contact-item"><i class="fab fa-linkedin"></i>LinkedIn</div>` : ''}
            </div>

            ${education.length > 0 && education[0].degree ? `
                <div class="cv-section">
                    <div class="cv-section-title">Education</div>
                    ${education.filter(e => e.degree).map(edu => `
                        <div class="cv-education-item">
                            <div class="cv-degree">${edu.degree}</div>
                            <div class="cv-institution">${edu.institution}</div>
                            <div class="cv-year">${edu.year}</div>
                        </div>
                    `).join('')}
                </div>
            ` : ''}

            ${skills.length > 0 ? `
                <div class="cv-section">
                    <div class="cv-section-title">Skills</div>
                    ${skills.map(skill => `<span class="cv-skill">${skill}</span>`).join('')}
                </div>
            ` : ''}
        </div>
        
        <div class="cv-main">
            ${personal.summary ? `
                <div class="cv-section">
                    <div class="cv-section-title">Professional Summary</div>
                    <div class="cv-summary">${personal.summary}</div>
                </div>
            ` : ''}

            ${experience.length > 0 && experience[0].title ? `
                <div class="cv-section">
                    <div class="cv-section-title">Work Experience</div>
                    ${experience.filter(e => e.title).map(exp => `
                        <div class="cv-experience-item">
                            <div class="cv-exp-title">${exp.title}</div>
                            <div class="cv-exp-company">${exp.company}</div>
                            <div class="cv-exp-duration">${exp.duration}</div>
                            ${exp.description ? `<p class="mt-2 small">${exp.description}</p>` : ''}
                        </div>
                    `).join('')}
                </div>
            ` : ''}
        </div>
    `;
}

// Classic Template
function generateClassicTemplate(data) {
    const { personal, education, experience, skills } = data;
    
    let contactInfo = [];
    if (personal.email) contactInfo.push(personal.email);
    if (personal.phone) contactInfo.push(personal.phone);
    if (personal.address) contactInfo.push(personal.address);
    if (personal.linkedin) contactInfo.push('LinkedIn');

    return `
        <div class="cv-header">
            ${(personal.firstName || personal.lastName) ? `
                <div class="cv-name">${personal.firstName} ${personal.lastName}</div>
            ` : ''}
            ${personal.jobTitle ? `<div class="cv-job-title">${personal.jobTitle}</div>` : ''}
            ${contactInfo.length > 0 ? `
                <div class="cv-contact-info">
                    ${contactInfo.map(c => `<span>${c}</span>`).join(' | ')}
                </div>
            ` : ''}
        </div>

        ${personal.summary ? `
            <div class="cv-section">
                <div class="cv-section-title">Summary</div>
                <p>${personal.summary}</p>
            </div>
        ` : ''}

        ${education.length > 0 && education[0].degree ? `
            <div class="cv-section">
                <div class="cv-section-title">Education</div>
                ${education.filter(e => e.degree).map(edu => `
                    <div class="cv-education-item">
                        <div class="cv-degree">${edu.degree}</div>
                        <div class="cv-institution">${edu.institution} | <span class="cv-year">${edu.year}</span></div>
                        ${edu.description ? `<p class="small mt-1">${edu.description}</p>` : ''}
                    </div>
                `).join('')}
            </div>
        ` : ''}

        ${experience.length > 0 && experience[0].title ? `
            <div class="cv-section">
                <div class="cv-section-title">Experience</div>
                ${experience.filter(e => e.title).map(exp => `
                    <div class="cv-experience-item">
                        <div class="cv-exp-title">${exp.title}</div>
                        <div class="cv-exp-company">${exp.company} | <span class="cv-exp-duration">${exp.duration}</span></div>
                        ${exp.description ? `<p class="small mt-1">${exp.description}</p>` : ''}
                    </div>
                `).join('')}
            </div>
        ` : ''}

        ${skills.length > 0 ? `
            <div class="cv-section">
                <div class="cv-section-title">Skills</div>
                ${skills.map(skill => `<span class="cv-skill">${skill}</span>`).join('')}
            </div>
        ` : ''}
    `;
}

// Creative Template
function generateCreativeTemplate(data) {
    const { personal, education, experience, skills } = data;
    
    return `
        <div class="cv-header">
            ${(personal.firstName || personal.lastName) ? `
                <div class="cv-name">${personal.firstName} ${personal.lastName}</div>
            ` : ''}
            ${personal.jobTitle ? `<div class="cv-job-title">${personal.jobTitle}</div>` : ''}
            
            <div class="cv-contact-bar">
                ${personal.email ? `<div class="cv-contact-item"><i class="fas fa-envelope"></i>${personal.email}</div>` : ''}
                ${personal.phone ? `<div class="cv-contact-item"><i class="fas fa-phone"></i>${personal.phone}</div>` : ''}
                ${personal.address ? `<div class="cv-contact-item"><i class="fas fa-map-marker-alt"></i>${personal.address}</div>` : ''}
                ${personal.linkedin ? `<div class="cv-contact-item"><i class="fab fa-linkedin"></i>LinkedIn</div>` : ''}
            </div>
        </div>
        
        <div class="cv-main-content">
            ${personal.summary ? `
                <div class="cv-section">
                    <div class="cv-section-title">About Me</div>
                    <div class="cv-summary">${personal.summary}</div>
                </div>
            ` : ''}

            ${education.length > 0 && education[0].degree ? `
                <div class="cv-section">
                    <div class="cv-section-title">Education</div>
                    ${education.filter(e => e.degree).map(edu => `
                        <div class="cv-education-item">
                            <div class="cv-degree">${edu.degree}</div>
                            <div class="cv-institution">${edu.institution} - ${edu.year}</div>
                            ${edu.description ? `<p class="small mt-1">${edu.description}</p>` : ''}
                        </div>
                    `).join('')}
                </div>
            ` : ''}

            ${experience.length > 0 && experience[0].title ? `
                <div class="cv-section">
                    <div class="cv-section-title">Work Experience</div>
                    ${experience.filter(e => e.title).map(exp => `
                        <div class="cv-experience-item">
                            <div class="cv-exp-title">${exp.title}</div>
                            <div class="cv-exp-company">${exp.company} | ${exp.duration}</div>
                            ${exp.description ? `<p class="small mt-1">${exp.description}</p>` : ''}
                        </div>
                    `).join('')}
                </div>
            ` : ''}

            ${skills.length > 0 ? `
                <div class="cv-section">
                    <div class="cv-section-title">My Skills</div>
                    ${skills.map(skill => `<span class="cv-skill">${skill}</span>`).join('')}
                </div>
            ` : ''}
        </div>
    `;
}

// Zoom preview
function zoomPreview(delta) {
    const preview = document.getElementById('cvPreview');
    zoomLevel = Math.max(0.5, Math.min(1.5, zoomLevel + delta));
    preview.style.transform = `scale(${zoomLevel})`;
}

// Download PDF
function downloadPDF() {
    const element = document.getElementById('cvPreview');
    const data = getFormData();
    const fileName = data.personal.firstName && data.personal.lastName 
        ? `${data.personal.firstName}_${data.personal.lastName}_CV.pdf`
        : 'My_CV.pdf';

    const opt = {
        margin: 0,
        filename: fileName,
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2, useCORS: true },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };

    // Add temporary class for PDF generation
    element.classList.remove('animate');
    
    html2pdf().set(opt).from(element).save().then(() => {
        element.classList.add('animate');
    });
}

// Load sample data
function loadSampleData() {
    // Personal info
    document.getElementById('firstName').value = 'John';
    document.getElementById('lastName').value = 'Doe';
    document.getElementById('jobTitle').value = 'Software Engineer';
    document.getElementById('email').value = 'john.doe@email.com';
    document.getElementById('phone').value = '+1 234 567 8900';
    document.getElementById('address').value = 'San Francisco, CA';
    document.getElementById('linkedin').value = 'https://linkedin.com/in/johndoe';
    document.getElementById('summary').value = 'Passionate software engineer with 5+ years of experience in full-stack development. Skilled in building scalable web applications and leading cross-functional teams. Committed to delivering high-quality solutions that drive business growth.';

    // Clear and add sample education
    const eduContainer = document.getElementById('educationFields');
    eduContainer.innerHTML = '';
    
    const sampleEducation = [
        { degree: 'Master of Science in Computer Science', institution: 'Stanford University', year: '2018', description: 'Specialized in Artificial Intelligence and Machine Learning. Graduated with Honors.' },
        { degree: 'Bachelor of Science in Software Engineering', institution: 'UC Berkeley', year: '2016', description: 'Dean\'s List recipient. Active member of Computer Science Club.' }
    ];
    
    sampleEducation.forEach((edu, index) => {
        if (index === 0) {
            document.querySelector('.edu-degree').value = edu.degree;
            document.querySelector('.edu-institution').value = edu.institution;
            document.querySelector('.edu-year').value = edu.year;
            document.querySelector('.edu-description').value = edu.description;
        } else {
            addEducationField();
            const entries = document.querySelectorAll('.education-entry');
            const entry = entries[entries.length - 1];
            entry.querySelector('.edu-degree').value = edu.degree;
            entry.querySelector('.edu-institution').value = edu.institution;
            entry.querySelector('.edu-year').value = edu.year;
            entry.querySelector('.edu-description').value = edu.description;
        }
    });

    // Clear and add sample experience
    const expContainer = document.getElementById('experienceFields');
    expContainer.innerHTML = '';
    
    const sampleExperience = [
        { title: 'Senior Software Engineer', company: 'Tech Innovators Inc.', duration: '2021 - Present', description: 'Lead development of cloud-based applications. Mentored junior developers and implemented CI/CD pipelines. Reduced deployment time by 40%.' },
        { title: 'Software Developer', company: 'Web Solutions LLC', duration: '2018 - 2021', description: 'Developed React and Node.js web applications. Collaborated with UX team to improve user experience. Implemented automated testing reducing bugs by 25%.' }
    ];
    
    sampleExperience.forEach((exp, index) => {
        if (index === 0) {
            document.querySelector('.exp-title').value = exp.title;
            document.querySelector('.exp-company').value = exp.company;
            document.querySelector('.exp-duration').value = exp.duration;
            document.querySelector('.exp-description').value = exp.description;
        } else {
            addExperienceField();
            const entries = document.querySelectorAll('.experience-entry');
            const entry = entries[entries.length - 1];
            entry.querySelector('.exp-title').value = exp.title;
            entry.querySelector('.exp-company').value = exp.company;
            entry.querySelector('.exp-duration').value = exp.duration;
            entry.querySelector('.exp-description').value = exp.description;
        }
    });

    // Sample skills
    skills = ['JavaScript', 'React', 'Node.js', 'Python', 'AWS', 'Docker', 'Git', 'MongoDB', 'TypeScript', 'Agile'];
    renderSkills();

    // Generate CV
    generateCV();
}

// Initialize with empty preview
document.addEventListener('DOMContentLoaded', function() {
    generateCV();
    
    // Auto-generate on input change
    const inputs = document.querySelectorAll('input, textarea');
    inputs.forEach(input => {
        input.addEventListener('input', generateCV);
    });
});

